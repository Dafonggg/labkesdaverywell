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
            'samples.*.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'samples.*.longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'samples.*.cuaca' => ['nullable', 'string', 'max:100'],
            'samples.*.suhu' => ['nullable', 'string', 'max:50'],
            'samples.*.catatan' => ['nullable', 'string', 'max:1000'],
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
            'samples.*.waktu_pengambilan.required' => 'Waktu pengambilan wajib diisi',
        ];
    }
}
