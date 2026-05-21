<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SampleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'registrasi_sample_id' => $this->registrasi_sample_id,
            'jadwal_sampling_id' => $this->jadwal_sampling_id,
            'jenis_sample' => $this->jenis_sample,
            'kondisi_sample' => $this->kondisi_sample,
            'metode_pengambilan' => $this->metode_pengambilan,
            'suhu' => $this->suhu,
            'cuaca' => $this->cuaca,
            'lokasi_pengambilan' => $this->lokasi_pengambilan,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'waktu_pengambilan' => $this->waktu_pengambilan?->toISOString(),
            'status' => $this->status,
            'hasil_uji' => HasilUjiResource::collection($this->whenLoaded('hasilUji')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
