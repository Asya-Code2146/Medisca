<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id ?? $this->route('user') : null;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['sometimes', 'required', Rule::in(['admin', 'guru_uks', 'pihak_sekolah'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim(strip_tags((string) $this->input('name'))),
            ]);
        }
        if ($this->has('email')) {
            $this->merge([
                'email' => trim(strip_tags((string) $this->input('email'))),
            ]);
        }
    }
}
