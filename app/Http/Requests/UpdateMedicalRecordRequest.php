<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'guru_uks'], true);
    }

    public function rules(): array
    {
        return [
            'nama_pasien' => ['sometimes', 'required', 'string', 'max:255'],
            'kelas_pasien' => ['nullable', 'string', 'max:50'],
            'complaint_category_id' => ['nullable', 'exists:complaint_categories,id'],
            'keluhan' => ['sometimes', 'required', 'string'],
            'medicine_id' => ['nullable', 'exists:medicines,id'],
            'jumlah_obat' => ['nullable', 'integer', 'min:0'],
            'tindakan' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'sedang_dirawat', 'selesai', 'dirujuk'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nama_pasien')) {
            $this->merge([
                'nama_pasien' => trim(strip_tags((string) $this->input('nama_pasien'))),
            ]);
        }
        if ($this->has('kelas_pasien') && $this->input('kelas_pasien')) {
            $this->merge([
                'kelas_pasien' => trim(strip_tags((string) $this->input('kelas_pasien'))),
            ]);
        }
        if ($this->has('keluhan')) {
            $this->merge([
                'keluhan' => trim(strip_tags((string) $this->input('keluhan'))),
            ]);
        }
        if ($this->has('tindakan') && $this->input('tindakan')) {
            $this->merge([
                'tindakan' => trim(strip_tags((string) $this->input('tindakan'))),
            ]);
        }
    }
}
