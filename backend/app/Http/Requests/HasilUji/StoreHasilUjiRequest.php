<?php

namespace App\Http\Requests\HasilUji;

use Illuminate\Foundation\Http\FormRequest;

class StoreHasilUjiRequest extends FormRequest
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
            'sample_id' => ['required', 'uuid', 'exists:samples,id'],
            'parameter_uji_id' => ['required', 'uuid', 'exists:parameter_uji,id'],
            'nilai_hasil' => ['required', 'numeric'],
            'metode_pengujian' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sample_id.required' => 'ID sample wajib diisi',
            'sample_id.exists' => 'Sample tidak ditemukan',
            'parameter_uji_id.required' => 'ID parameter uji wajib diisi',
            'parameter_uji_id.exists' => 'Parameter uji tidak ditemukan',
            'nilai_hasil.required' => 'Nilai hasil wajib diisi',
            'nilai_hasil.numeric' => 'Nilai hasil harus berupa angka',
            'metode_pengujian.required' => 'Metode pengujian wajib diisi',
        ];
    }
}
