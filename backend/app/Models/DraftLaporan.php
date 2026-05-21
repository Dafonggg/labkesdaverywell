<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DraftLaporan extends Model
{
    use HasUuids;

    protected $table = 'draft_laporan';

    protected $fillable = [
        'permohonan_id',
        'analis_id',
        'nomor_laporan',
        'status',
        'file_path',
        'catatan',
        'dibuat_pada',
    ];

    protected function casts(): array
    {
        return [
            'dibuat_pada' => 'datetime',
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
     * The analyst who created the draft.
     */
    public function analis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analis_id');
    }

    /**
     * Approval records.
     */
    public function persetujuan(): HasMany
    {
        return $this->hasMany(PersetujuanLaporan::class, 'draft_laporan_id');
    }

    /**
     * Final report generated from this draft.
     */
    public function laporanFinal(): HasOne
    {
        return $this->hasOne(LaporanFinal::class, 'draft_laporan_id');
    }
}
