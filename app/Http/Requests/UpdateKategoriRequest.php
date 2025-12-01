<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKategoriRequest extends FormRequest
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
        $id = $this->route('record') ?? $this->route('kategori');

        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('kategoris', 'nama_kategori')->ignore($id),
                'regex:/^(Domba|Kambing|Sapi)\s.+$/', // Must start with Domba, Kambing, or Sapi
            ],
            'image' => [
                'nullable',
                'string',
                'max:500',
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
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.min' => 'Nama kategori minimal 3 karakter.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'nama_kategori.regex' => 'Nama kategori harus diawali dengan Domba, Kambing, atau Sapi.',
        ];
    }
}
