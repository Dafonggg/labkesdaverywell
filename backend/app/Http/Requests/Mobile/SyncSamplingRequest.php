<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class SyncSamplingRequest extends FormRequest
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
            'samples' => ['required', 'array', 'min:1'],
            'samples.*.sync_id' => ['required', 'uuid'],
            'samples.*.jadwal_sampling_id' => ['required', 'uuid', 'exists:jadwal_sampling,id'],
            'samples.*.jenis_sample' => ['required', 'string', 'max:255'],
            'samples.*.kondisi_sample' => ['nullable', 'string', 'in:baik,rusak,tidak_sesuai'],
            'samples.*.metode_pengambilan' => ['nullable', 'string', 'max:255'],
            'samples.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'samples.*.longitude' => ['required', 'numeric', 'between:-180,180'],
            'samples.*.lokasi_pengambilan' => ['required', 'string', 'max:500'],
            'samples.*.cuaca' => ['nullable', 'string', 'max:100'],
            'samples.*.suhu' => ['nullable', 'string', 'max:50'],
            'samples.*.catatan' => ['nullable', 'string', 'max:5000'],
            'samples.*.waktu_pengambilan' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'samples.required' => 'Data samples wajib diisi',
            'samples.min' => 'Minimal 1 sample harus dikirim',
            'samples.*.sync_id.required' => 'Sync ID wajib diisi untuk setiap sample',
            'samples.*.jadwal_sampling_id.required' => 'ID jadwal sampling wajib diisi',
            'samples.*.jadwal_sampling_id.exists' => 'Jadwal sampling tidak ditemukan',
            'samples.*.jenis_sample.required' => 'Jenis sample wajib diisi',
            'samples.*.latitude.required' => 'Koordinat GPS (latitude) wajib diisi saat sampling lapangan',
            'samples.*.longitude.required' => 'Koordinat GPS (longitude) wajib diisi saat sampling lapangan',
            'samples.*.lokasi_pengambilan.required' => 'Lokasi pengambilan wajib diisi',
            'samples.*.waktu_pengambilan.required' => 'Waktu pengambilan wajib diisi',
        ];
    }
}
