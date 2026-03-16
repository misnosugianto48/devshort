<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Later restricted by Plan limits in Phase 4
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048'],
            'title' => ['nullable', 'string', 'max:255'],
            // Future phase additions
            // 'custom_alias' => ['nullable', 'string', 'alpha_dash', 'min:3', 'max:30', 'unique:links,short_code'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'original_url.required' => 'URL tujuan tidak boleh kosong.',
            'original_url.url' => 'Format URL tujuan tidak valid.',
            'original_url.max' => 'URL tujuan terlalu panjang (maksimal 2048 karakter).',
            'title.max' => 'Judul tautan terlalu panjang (maksimal 255 karakter).',
        ];
    }
}
