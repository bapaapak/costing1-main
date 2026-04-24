<?php

namespace App\Http\Requests\Database;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateProjectDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $a04 = $this->input('a04');
        $a05 = $this->input('a05');

        if ($a04 === 'ada' || $a05 === 'ada') {
            $this->merge([
                'a00' => 'ada',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'a00' => ['required', 'in:ada,belum_ada'],
            'a00_received_date' => ['nullable', 'date'],
            'a00_document_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'a04' => ['required', 'in:ada,belum_ada'],
            'a04_received_date' => ['nullable', 'date'],
            'a04_document_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'a05' => ['required', 'in:ada,belum_ada'],
            'a05_received_date' => ['nullable', 'date'],
            'a05_document_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $a04 = $this->input('a04');
            $a05 = $this->input('a05');

            if ($a04 === 'ada' && $a05 === 'ada') {
                $validator->errors()->add(
                    'a04',
                    'A04 dan A05 tidak bisa keduanya "Ada". Project hanya bisa menjadi salah satu: A04 (Cancelled) atau A05 (Die Go).'
                );
            }
        });
    }
}
