<?php

namespace App\Http\Requests\Permohonan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermohonanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_pemohon' => ['sometimes', 'string', 'in:instansi,perorangan'],
            'nama_instansi' => ['nullable', 'string', 'max:255'],
            'nama_pemohon' => ['sometimes', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:20'],
            'alamat' => ['sometimes', 'string', 'max:500'],
            'jenis_sample' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
