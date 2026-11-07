<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSystemReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'category' => ['required', Rule::in(['bug', 'error', 'mencurigakan'])],
            'category_label' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'page' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'solution' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category_label' => trim(strip_tags((string) $this->input('category_label'))),
            'title' => trim(strip_tags((string) $this->input('title'))),
            'page' => trim(strip_tags((string) $this->input('page'))),
            'desc' => trim(strip_tags((string) $this->input('desc'))),
            'solution' => trim(strip_tags((string) $this->input('solution'))),
        ]);
    }
}
