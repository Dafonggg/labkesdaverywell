<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HasilUjiResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sample_id' => $this->sample_id,
            'parameter_uji_id' => $this->parameter_uji_id,
            'analis_id' => $this->analis_id,
            'nilai_hasil' => $this->nilai_hasil,
            'status_hasil' => $this->status_hasil,
            'metode_pengujian' => $this->metode_pengujian,
            'catatan' => $this->catatan,
            'status_qc' => $this->status_qc,
            'diuji_pada' => $this->diuji_pada?->toISOString(),
            'sample' => new SampleResource($this->whenLoaded('sample')),
            'parameter_uji' => new ParameterUjiResource($this->whenLoaded('parameterUji')),
            'analis' => new UserResource($this->whenLoaded('analis')),
            'verifikasi_qc' => VerifikasiQcResource::collection($this->whenLoaded('verifikasiQc')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
