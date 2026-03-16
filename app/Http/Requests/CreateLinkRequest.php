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
            'custom_alias' => [
                'nullable',
                'string',
                'alpha_dash',
                'min:3',
                'max:30',
                'unique:links,short_code',
                'not_in:api,admin,dashboard,login,register,links,billing,settings,profile,logout,password,pricing,features',
            ],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'password' => ['nullable', 'string', 'min:4', 'max:255'],
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
            'custom_alias.alpha_dash' => 'Custom alias hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'custom_alias.min' => 'Custom alias minimal 3 karakter.',
            'custom_alias.max' => 'Custom alias maksimal 30 karakter.',
            'custom_alias.unique' => 'Custom alias ini sudah digunakan. Silakan pilih yang lain.',
            'custom_alias.not_in' => 'Kata ini merupakan reservasi sistem dan tidak dapat digunakan sebagai alias.',
            'password.min' => 'Password minimal harus 4 karakter.',
        ];
    }
}
