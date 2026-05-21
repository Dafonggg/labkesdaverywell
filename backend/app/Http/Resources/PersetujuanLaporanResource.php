<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersetujuanLaporanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'draft_laporan_id' => $this->draft_laporan_id,
            'kepala_uptd_id' => $this->kepala_uptd_id,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'approved_at' => $this->approved_at?->toISOString(),
            'kepala_uptd' => new UserResource($this->whenLoaded('kepalaUptd')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
