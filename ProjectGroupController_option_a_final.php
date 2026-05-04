<?php

namespace App\Http\Controllers;

use App\Models\CostingData;
use App\Models\DocumentRevision;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProjectGroupController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);

        if (!in_array($perPage, [10, 15, 25, 50], true)) {
            $perPage = 10;
        }

        /*
         * OPSI A:
         * Parent project hanya berdasarkan:
         * - Business Category
         * - Customer
         * - Model
         *
         * Part Number, Part Name, Revision, Status, dan Tanggal masuk ke child.
         * Jadi 1 model yang sama tidak akan pecah hanya karena part number berbeda.
         */
        $revisions = DocumentRevision::with(['project.product'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $costingByRevisionId = CostingData::with(['customer', 'product'])
            ->whereNotNull('tracking_revision_id')
            ->get()
            ->keyBy('tracking_revision_id');

        $children = $revisions->map(function (DocumentRevision $revision) use ($costingByRevisionId) {
            $project = $revision->project;
            $costing = $costingByRevisionId->get($revision->id);

            $businessCategory = $this->cleanText(
                $project->product->name
                    ?? $project->product->business_category
                    ?? $project->business_category
                    ?? $costing->product->name
                    ?? 'WIRING HARNESS'
            );

            $customer = $this->cleanText(
                $costing->customer->name
                    ?? $project->customer
                    ?? '-'
            );

            $model = $this->normalizeModel(
                $costing->model
                    ?? $project->model
                    ?? '-'
            );

            $partNumber = $this->cleanText(
                $costing->assy_no
                    ?? $project->part_number
                    ?? '-'
            );

            $partName = $this->cleanText(
                $costing->assy_name
                    ?? $project->part_name
                    ?? '-'
            );

            $docStatus = $this->documentStatus($revision);
            $costingStatus = $this->costingStatus($revision, $costing);

            return (object) [
                'revision' => $revision,
                'project' => $project,
                'costing' => $costing,

                // Parent key fields
                'business_category' => $businessCategory,
                'customer' => $customer,
                'model' => $model,

                // Child fields
                'part_number' => $partNumber,
                'part_name' => $partName,
                'revision_label' => $revision->version_label
                    ?: ('V' . (string) ($revision->revision_number ?? 0)),
                'revision_count' => (int) ($revision->revision_number ?? 0),
                'doc_status' => $docStatus,
                'costing_status' => $costingStatus,
                'pic_engineering' => $this->cleanText(
                    $project->pic_engineering
                        ?? $project->engineering_pic
                        ?? $costing->pic_engineering
                        ?? '-'
                ),
                'pic_marketing' => $this->cleanText(
                    $project->pic_marketing
                        ?? $project->marketing_pic
                        ?? $costing->pic_marketing
                        ?? '-'
                ),
                'created_at' => $revision->created_at ?? $project->created_at ?? $costing->created_at ?? null,
                'updated_at' => $revision->updated_at ?? $project->updated_at ?? $costing->updated_at ?? null,
            ];
        });

        if ($search !== '') {
            $needle = mb_strtolower($this->cleanText($search));
            $children = $children->filter(function ($item) use ($needle) {
                $text = implode(' ', [
                    $item->business_category,
                    $item->customer,
                    $item->model,
                    $item->part_number,
                    $item->part_name,
                    $item->revision_label,
                    $item->pic_engineering,
                    $item->pic_marketing,
                    $item->doc_status,
                    $item->costing_status,
                ]);

                return str_contains(mb_strtolower($text), $needle);
            })->values();
        }

        $groups = $children
            ->groupBy(fn ($item) => $this->groupKey($item->business_category, $item->customer, $item->model))
            ->map(function (Collection $items) {
                $first = $items->first();

                return (object) [
                    'key' => $this->groupKey($first->business_category, $first->customer, $first->model),
                    'business_category' => $first->business_category,
                    'customer' => $first->customer,
                    'model' => $first->model,
                    'project_name' => $this->joinUnique($items->pluck('part_name')),
                    'pic_engineering' => $this->joinUnique($items->pluck('pic_engineering')),
                    'pic_marketing' => $this->joinUnique($items->pluck('pic_marketing')),
                    'created_at' => $items->sortBy('created_at')->first()->created_at,
                    'updated_at' => $items->sortByDesc('updated_at')->first()->updated_at,
                    'total_part_number' => $items->pluck('part_number')->filter()->unique()->count(),
                    'total_items' => $items->count(),
                    'pending_count' => $items->where('costing_status', 'Pending Form Costing')->count(),
                    'costing_done_count' => $items->where('costing_status', 'Sudah Costing')->count(),
                    'canceled_count' => $items->where('costing_status', 'Canceled')->count(),
                    'a00_count' => $items->where('doc_status', 'A00 Ada')->count(),
                    'a04_count' => $items->where('doc_status', 'A04 Ada')->count(),
                    'a05_count' => $items->where('doc_status', 'A05 Ada')->count(),
                    'items' => $items
                        ->sortBy([
                            ['part_number', 'asc'],
                            ['revision_label', 'asc'],
                        ])
                        ->values(),
                ];
            })
            ->sortByDesc('updated_at')
            ->values();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedGroups = new LengthAwarePaginator(
            $groups->forPage($currentPage, $perPage)->values(),
            $groups->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('projects.index', [
            'pagedGroups' => $pagedGroups,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    private function documentStatus(DocumentRevision $revision): string
    {
        if (($revision->a05 ?? null) === 'ada') {
            return 'A05 Ada';
        }

        if (($revision->a04 ?? null) === 'ada') {
            return 'A04 Ada';
        }

        if (($revision->a00 ?? null) === 'ada') {
            return 'A00 Ada';
        }

        return 'Belum Ada';
    }

    private function costingStatus(DocumentRevision $revision, ?CostingData $costing): string
    {
        if ($costing) {
            return 'Sudah Costing';
        }

        if (($revision->a04 ?? null) === 'ada') {
            return 'Canceled';
        }

        return 'Pending Form Costing';
    }

    private function groupKey(string $businessCategory, string $customer, string $model): string
    {
        return implode('|', [
            $this->normalizeKey($businessCategory),
            $this->normalizeKey($customer),
            $this->normalizeKey($model),
        ]);
    }

    private function normalizeModel(?string $value): string
    {
        return strtoupper($this->cleanText((string) $value));
    }

    private function normalizeKey(?string $value): string
    {
        $value = mb_strtolower($this->cleanText((string) $value));
        $value = preg_replace('/\s+/', ' ', $value);
        return trim($value);
    }

    private function cleanText(?string $value): string
    {
        $value = (string) $value;
        $value = str_replace(["\r", "\n", "\t"], ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        return trim($value) !== '' ? trim($value) : '-';
    }

    private function joinUnique(Collection $values): string
    {
        $filtered = $values
            ->filter(fn ($value) => filled($value) && $value !== '-')
            ->map(fn ($value) => $this->cleanText((string) $value))
            ->unique()
            ->values();

        if ($filtered->isEmpty()) {
            return '-';
        }

        if ($filtered->count() > 3) {
            return $filtered->take(3)->implode(', ') . ' +' . ($filtered->count() - 3);
        }

        return $filtered->implode(', ');
    }
}
