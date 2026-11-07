<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateComplaintCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'guru_uks'], true);
    }

    public function rules(): array
    {
        $id = $this->route('category') ? $this->route('category')->id ?? $this->route('category') : null;

        return [
            'nama_keluhan' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('complaint_categories', 'nama_keluhan')->ignore($id)],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nama_keluhan')) {
            $this->merge([
                'nama_keluhan' => trim(strip_tags((string) $this->input('nama_keluhan'))),
            ]);
        }
        if ($this->has('deskripsi') && $this->input('deskripsi')) {
            $this->merge([
                'deskripsi' => trim(strip_tags((string) $this->input('deskripsi'))),
            ]);
        }
    }
}
