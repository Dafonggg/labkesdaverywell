<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'permohonan_id' => $this->permohonan_id,
            'metode_pembayaran' => $this->metode_pembayaran,
            'jumlah' => $this->jumlah,
            'status' => $this->status,
            'tanggal_bayar' => $this->tanggal_bayar?->toISOString(),
            'bukti_pembayaran' => $this->bukti_pembayaran,
            'dicatat_oleh' => $this->dicatat_oleh,
            'pencatat' => new UserResource($this->whenLoaded('pencatat')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
