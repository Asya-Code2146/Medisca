<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'guru_uks'], true);
    }

    public function rules(): array
    {
        return [
            'nama_keluhan' => ['required', 'string', 'max:255', 'unique:complaint_categories,nama_keluhan'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_keluhan' => trim(strip_tags((string) $this->input('nama_keluhan'))),
            'deskripsi' => $this->input('deskripsi') ? trim(strip_tags((string) $this->input('deskripsi'))) : null,
        ]);
    }
}
