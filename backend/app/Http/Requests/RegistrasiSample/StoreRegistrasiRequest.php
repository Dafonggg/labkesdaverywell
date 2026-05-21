<?php

namespace App\Http\Requests\RegistrasiSample;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrasiRequest extends FormRequest
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
            'permohonan_id' => ['required', 'uuid', 'exists:permohonan_pengujian,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'permohonan_id.required' => 'ID permohonan wajib diisi',
            'permohonan_id.exists' => 'Permohonan tidak ditemukan',
        ];
    }
}
