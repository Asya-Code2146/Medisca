<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'guru_uks'], true);
    }

    public function rules(): array
    {
        return [
            'nama_pasien' => ['required', 'string', 'max:255'],
            'kelas_pasien' => ['nullable', 'string', 'max:50'],
            'complaint_category_id' => ['nullable', 'exists:complaint_categories,id'],
            'keluhan' => ['required', 'string'],
            'medicine_id' => ['nullable', 'exists:medicines,id'],
            'jumlah_obat' => ['nullable', 'integer', 'min:1'],
            'tindakan' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['pending', 'sedang_dirawat', 'selesai', 'dirujuk'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_pasien' => trim(strip_tags((string) $this->input('nama_pasien'))),
            'kelas_pasien' => $this->input('kelas_pasien') ? trim(strip_tags((string) $this->input('kelas_pasien'))) : null,
            'keluhan' => trim(strip_tags((string) $this->input('keluhan'))),
            'tindakan' => $this->input('tindakan') ? trim(strip_tags((string) $this->input('tindakan'))) : null,
        ]);
    }
}
