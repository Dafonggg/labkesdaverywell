<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DraftLaporanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'permohonan_id' => $this->permohonan_id,
            'analis_id' => $this->analis_id,
            'nomor_laporan' => $this->nomor_laporan,
            'status' => $this->status,
            'file_path' => $this->file_path,
            'catatan' => $this->catatan,
            'dibuat_pada' => $this->dibuat_pada?->toISOString(),
            'permohonan' => new PermohonanResource($this->whenLoaded('permohonan')),
            'analis' => new UserResource($this->whenLoaded('analis')),
            'persetujuan' => PersetujuanLaporanResource::collection($this->whenLoaded('persetujuan')),
            'laporan_final' => new LaporanFinalResource($this->whenLoaded('laporanFinal')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
