<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListDistribusiRequest extends FormRequest
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
            'nama' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'shohibul_qurban' => [
                'boolean',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
            'request' => [
                'required',
                'array',
                'min:1',
            ],
            'request.*' => [
                'string',
                'in:Daging,Daging Domba,Daging Kambing,Daging Sapi,Jeroan,Kepala & Kaki,Buntut',
            ],
            'alamat' => [
                'required',
                'string',
                'max:500',
                'min:5',
            ],
            'terbungkus' => [
                'boolean',
            ],
            'terdistribusi' => [
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
            'nama.required' => 'Nama penerima wajib diisi.',
            'nama.min' => 'Nama penerima minimal 3 karakter.',
            'nama.max' => 'Nama penerima maksimal 255 karakter.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'jumlah.max' => 'Jumlah maksimal 1000.',
            'request.required' => 'Permintaan bagian daging wajib dipilih.',
            'request.min' => 'Minimal pilih 1 jenis bagian daging.',
            'request.*.in' => 'Jenis bagian daging yang dipilih tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.min' => 'Alamat minimal 5 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
        ];
    }
}
