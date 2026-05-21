<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalSamplingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'permohonan_id' => $this->permohonan_id,
            'petugas_lapangan_id' => $this->petugas_lapangan_id,
            'tanggal_sampling' => $this->tanggal_sampling?->toDateString(),
            'jam_sampling' => $this->jam_sampling,
            'lokasi' => $this->lokasi,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'petugas_lapangan' => new UserResource($this->whenLoaded('petugasLapangan')),
            'permohonan' => new PermohonanResource($this->whenLoaded('permohonan')),
            'samples' => SampleResource::collection($this->whenLoaded('samples')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
