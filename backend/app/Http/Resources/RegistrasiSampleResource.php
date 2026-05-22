<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrasiSampleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'permohonan_id' => $this->permohonan_id,
            'nomor_registrasi' => $this->nomor_registrasi,
            'kode_sample' => $this->kode_sample,
            'tanggal_registrasi' => $this->tanggal_registrasi?->toISOString(),
            'status' => $this->status,
            'dibuat_oleh' => $this->dibuat_oleh,
            'pembuat' => new UserResource($this->whenLoaded('pembuat')),
            'permohonan' => new PermohonanResource($this->whenLoaded('permohonan')),
            'samples' => SampleResource::collection($this->whenLoaded('samples')),
            'sample_id' => $this->samples->first()?->id,
            'sample' => $this->samples->isNotEmpty() ? new SampleResource($this->samples->first()) : null,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
