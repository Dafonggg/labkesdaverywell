<?php

namespace App\Http\Requests\Qc;

use Illuminate\Foundation\Http\FormRequest;

class ApproveQcRequest extends FormRequest
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
            'hasil_uji_id' => ['required', 'uuid', 'exists:hasil_uji,id'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hasil_uji_id.required' => 'ID hasil uji wajib diisi',
            'hasil_uji_id.exists' => 'Hasil uji tidak ditemukan',
        ];
    }
}
