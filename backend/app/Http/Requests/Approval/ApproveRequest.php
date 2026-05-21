<?php

namespace App\Http\Requests\Approval;

use Illuminate\Foundation\Http\FormRequest;

class ApproveRequest extends FormRequest
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
            'laporan_id' => ['required', 'uuid', 'exists:draft_laporan,id'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'laporan_id.required' => 'ID laporan wajib diisi',
            'laporan_id.exists' => 'Laporan tidak ditemukan',
        ];
    }
}
