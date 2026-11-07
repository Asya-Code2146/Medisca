<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'guru_uks'], true);
    }

    public function rules(): array
    {
        return [
            'nama_obat' => ['sometimes', 'required', 'string', 'max:255'],
            'stok' => ['sometimes', 'required', 'integer', 'min:0'],
            'satuan' => ['sometimes', 'required', 'string', 'max:50'],
            'tgl_kedaluwarsa' => ['nullable', 'date'],
            'status_stok' => ['nullable', Rule::in(['aman', 'menipis', 'kritis'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nama_obat')) {
            $this->merge([
                'nama_obat' => trim(strip_tags((string) $this->input('nama_obat'))),
            ]);
        }
        if ($this->has('satuan')) {
            $this->merge([
                'satuan' => trim(strip_tags((string) $this->input('satuan'))),
            ]);
        }
    }
}
