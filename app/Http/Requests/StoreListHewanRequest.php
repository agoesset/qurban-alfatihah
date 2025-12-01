<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListHewanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Update with proper authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_hewan' => [
                'required',
                'string',
                'max:255',
                'unique:list_hewans,kode_hewan',
                'regex:/^[A-Z0-9\-]+$/', // Only uppercase letters, numbers, and hyphens
            ],
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'bobot' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999.99',
            ],
            'penyembelihan' => [
                'boolean',
            ],
            'pengulitan' => [
                'boolean',
            ],
            'penimbangan' => [
                'boolean',
            ],
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
            'kode_hewan.required' => 'Kode hewan wajib diisi.',
            'kode_hewan.unique' => 'Kode hewan sudah digunakan.',
            'kode_hewan.regex' => 'Kode hewan hanya boleh mengandung huruf besar, angka, dan tanda hubung.',
            'kategori_id.required' => 'Kategori hewan wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'bobot.required' => 'Bobot hewan wajib diisi.',
            'bobot.min' => 'Bobot hewan minimal 0.01 kg.',
            'bobot.max' => 'Bobot hewan maksimal 999.99 kg.',
        ];
    }
}
