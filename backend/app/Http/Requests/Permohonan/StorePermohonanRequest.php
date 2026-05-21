<?php

namespace App\Http\Requests\Permohonan;

use Illuminate\Foundation\Http\FormRequest;

class StorePermohonanRequest extends FormRequest
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
            'jenis_pemohon' => ['required', 'string', 'in:instansi,perorangan'],
            'nama_instansi' => ['nullable', 'string', 'max:255'],
            'nama_pemohon' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'jenis_sample' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jenis_pemohon.required' => 'Jenis pemohon wajib diisi',
            'jenis_pemohon.in' => 'Jenis pemohon harus instansi atau perorangan',
            'nama_pemohon.required' => 'Nama pemohon wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'jenis_sample.required' => 'Jenis sample wajib diisi',
        ];
    }
}
