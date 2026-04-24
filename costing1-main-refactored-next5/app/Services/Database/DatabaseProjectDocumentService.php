<?php

namespace App\Services\Database;

use App\Models\CostingData;
use App\Models\DocumentRevision;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseProjectDocumentService
{
    public function getPageData(Request $request): array
    {
        $search = trim((string) $request->input('search', ''));
        $statusFilter = trim((string) $request->input('status', ''));
        $perPage = (int) $request->input('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50], true)) {
            $perPage = 15;
        }

        $costingData = CostingData::with(['trackingRevision.project', 'product', 'customer'])->get();

        $counts = [
            'a00Count' => 0,
            'a04Count' => 0,
            'a05Count' => 0,
        ];

        $rows = $costingData
            ->map(function ($item) use (&$counts) {
                $revision = $item->trackingRevision;
                if (!$revision) {
                    return null;
                }

                $status = 'none';
                if (($revision->a05 ?? null) === 'ada') {
                    $status = 'a05';
                    $counts['a05Count']++;
                } elseif (($revision->a04 ?? null) === 'ada') {
                    $status = 'a04';
                    $counts['a04Count']++;
                } elseif (($revision->a00 ?? null) === 'ada') {
                    $status = 'a00';
                    $counts['a00Count']++;
                }

                return (object) [
                    'revision' => $revision,
                    'project' => $revision->project,
                    'costingData' => $item,
                    'status' => $status,
                ];
            })
            ->filter()
            ->values();

        $rows = $this->filterRows($rows, $search, $statusFilter);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedRows = new LengthAwarePaginator(
            $rows->forPage($currentPage, $perPage),
            $rows->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return array_merge($counts, compact('pagedRows', 'search', 'statusFilter', 'perPage'));
    }

    public function update(DocumentRevision $revision, array $validated, Request $request): void
    {
        foreach (['a00', 'a04', 'a05'] as $prefix) {
            $status = $validated[$prefix];
            $revision->$prefix = $status;

            $dateField = $prefix . '_received_date';
            $fileField = $prefix . '_document_file';
            $pathField = $prefix . '_document_file_path';
            $nameField = $prefix . '_document_original_name';

            if ($status === 'ada') {
                $revision->$dateField = $validated[$dateField] ?? null;

                if ($request->hasFile($fileField)) {
                    $oldPath = $revision->$pathField;
                    $file = $request->file($fileField);
                    $path = $file->storeAs(
                        'tracking-documents/' . $prefix,
                        now()->format('YmdHis') . '-' . Str::uuid() . '.' . $file->getClientOriginalExtension()
                    );

                    $revision->$pathField = $path;
                    $revision->$nameField = $file->getClientOriginalName();

                    if (!empty($oldPath)) {
                        Storage::delete($oldPath);
                    }
                }
            } else {
                $this->deleteStoredDocument($revision->$pathField);
                $revision->$dateField = null;
                $revision->$pathField = null;
                $revision->$nameField = null;
            }
        }

        $revision->save();
    }

    public function destroy(DocumentRevision $revision): void
    {
        foreach (['a00', 'a04', 'a05'] as $prefix) {
            $this->deleteStoredDocument($revision->{$prefix . '_document_file_path'});

            $revision->$prefix = null;
            $revision->{$prefix . '_received_date'} = null;
            $revision->{$prefix . '_document_original_name'} = null;
            $revision->{$prefix . '_document_file_path'} = null;
        }

        $revision->save();
    }

    private function filterRows(Collection $rows, string $search, string $statusFilter): Collection
    {
        if ($search !== '') {
            $searchLower = mb_strtolower($search);
            $rows = $rows->filter(function ($row) use ($searchLower) {
                $text = implode(' ', array_filter([
                    $row->costingData->customer->name ?? '',
                    $row->costingData->model ?? '',
                    $row->costingData->assy_name ?? '',
                    $row->project->customer ?? '',
                    $row->project->model ?? '',
                    $row->project->part_name ?? '',
                    $row->revision->version_label ?? '',
                ]));

                return str_contains(mb_strtolower($text), $searchLower);
            })->values();
        }

        if ($statusFilter !== '') {
            $rows = $rows->filter(fn ($row) => $row->status === $statusFilter)->values();
        }

        return $rows;
    }

    private function deleteStoredDocument(?string $path): void
    {
        if (!empty($path)) {
            Storage::delete($path);
        }
    }
}
