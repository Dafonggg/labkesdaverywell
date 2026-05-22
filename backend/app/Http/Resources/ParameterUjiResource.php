<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParameterUjiResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_parameter' => $this->nama_parameter,
            'satuan' => $this->satuan,
            'kategori' => $this->kategori,
            'tipe_pengujian' => $this->tipe_pengujian,
            'metode_uji' => $this->metode_uji,
            'baku_mutu_min' => $this->baku_mutu_min,
            'baku_mutu_max' => $this->baku_mutu_max,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
