<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermohonanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nomor_permohonan' => $this->nomor_permohonan,
            'jenis_pemohon' => $this->jenis_pemohon,
            'nama_instansi' => $this->nama_instansi,
            'nama_pemohon' => $this->nama_pemohon,
            'email' => $this->email,
            'phone' => $this->phone,
            'alamat' => $this->alamat,
            'jenis_sample' => $this->jenis_sample,
            'total_biaya' => $this->total_biaya,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'dibuat_oleh' => $this->dibuat_oleh,
            'pemohon' => new UserResource($this->whenLoaded('pemohon')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'jadwal_sampling' => JadwalSamplingResource::collection($this->whenLoaded('jadwalSampling')),
            'registrasi_sample' => RegistrasiSampleResource::collection($this->whenLoaded('registrasiSample')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
