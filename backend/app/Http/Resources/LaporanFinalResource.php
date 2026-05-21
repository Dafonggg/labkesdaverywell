<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LaporanFinalResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'draft_laporan_id' => $this->draft_laporan_id,
            'nomor_laporan' => $this->nomor_laporan,
            'file_path' => $this->file_pdf,
            'file_pdf' => $this->file_pdf,
            'hash_sha256' => $this->hash_sha256,
            'status' => $this->is_final ? 'final' : 'draft',
            'is_final' => $this->is_final,
            'finalized_at' => $this->finalized_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
