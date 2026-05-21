<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    protected $table = 'payments';

    protected $fillable = [
        'permohonan_id',
        'metode_pembayaran',
        'jumlah',
        'status',
        'tanggal_bayar',
        'bukti_pembayaran',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    /**
     * The related permohonan.
     */
    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(PermohonanPengujian::class, 'permohonan_id');
    }

    /**
     * User who recorded the payment.
     */
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
