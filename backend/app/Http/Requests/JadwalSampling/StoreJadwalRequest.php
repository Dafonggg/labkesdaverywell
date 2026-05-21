<?php

namespace App\Http\Requests\JadwalSampling;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
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
            'petugas_lapangan_id' => ['required', 'uuid', 'exists:users,id'],
            'tanggal_sampling' => ['required', 'date', 'after_or_equal:today'],
            'jam_sampling' => ['nullable', 'string'],
            'lokasi' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'catatan' => ['nullable', 'string', 'max:1000'],
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
            'petugas_lapangan_id.required' => 'ID petugas lapangan wajib diisi',
            'petugas_lapangan_id.exists' => 'Petugas lapangan tidak ditemukan',
            'tanggal_sampling.required' => 'Tanggal sampling wajib diisi',
            'tanggal_sampling.after_or_equal' => 'Tanggal sampling tidak boleh di masa lalu',
            'lokasi.required' => 'Lokasi wajib diisi',
        ];
    }
}
