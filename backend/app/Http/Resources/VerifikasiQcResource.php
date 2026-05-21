<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifikasiQcResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hasil_uji_id' => $this->hasil_uji_id,
            'qc_id' => $this->qc_id,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'verified_by' => $this->qc_id,
            'verified_at' => $this->diverifikasi_pada?->toISOString(),
            'diverifikasi_pada' => $this->diverifikasi_pada?->toISOString(),
            'qc_officer' => new UserResource($this->whenLoaded('qcOfficer')),
            'hasil_uji' => new HasilUjiResource($this->whenLoaded('hasilUji')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
