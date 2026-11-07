<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'guru_uks'], true);
    }

    public function rules(): array
    {
        return [
            'nama_obat' => ['required', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'tgl_kedaluwarsa' => ['nullable', 'date'],
            'status_stok' => ['nullable', Rule::in(['aman', 'menipis', 'kritis'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_obat' => trim(strip_tags((string) $this->input('nama_obat'))),
            'satuan' => trim(strip_tags((string) $this->input('satuan'))),
        ]);
    }
}
