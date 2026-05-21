<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LaporanFinal extends Model
{
    use HasUuids;

    protected $table = 'laporan_final';

    protected $fillable = [
        'draft_laporan_id',
        'nomor_laporan',
        'file_pdf',
        'hash_sha256',
        'is_final',
        'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'is_final' => 'boolean',
            'finalized_at' => 'datetime',
        ];
    }

    /**
     * The source draft report.
     */
    public function draftLaporan(): BelongsTo
    {
        return $this->belongsTo(DraftLaporan::class, 'draft_laporan_id');
    }

    /**
     * Archive record.
     */
    public function arsip(): HasOne
    {
        return $this->hasOne(ArsipLaporan::class, 'laporan_final_id');
    }
}
