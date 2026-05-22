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
            'permohonan_id' => [
                'required',
                'uuid',
                'exists:permohonan_pengujian,id',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\RegistrasiSample::where('permohonan_id', $value)
                        ->whereHas('samples.hasilUji')
                        ->exists();
                    if ($exists) {
                        $fail('Permohonan ini sudah terdaftar dan memiliki parameter pengujian.');
                    }
                }
            ],
            'jenis_sample' => ['required', 'string'],
            'nama_sample' => ['required', 'string'],
            'parameters' => ['required', 'array'],
            'parameters.*' => ['required', 'string'],
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
            'permohonan_id.unique' => 'Permohonan ini sudah terdaftar sebelumnya',
            'jenis_sample.required' => 'Jenis sampel wajib diisi',
            'nama_sample.required' => 'Nama sampel wajib diisi',
            'parameters.required' => 'Parameter pengujian wajib dipilih',
        ];
    }
}
