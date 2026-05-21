<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersetujuanLaporan extends Model
{
    use HasUuids;

    protected $table = 'persetujuan_laporan';

    protected $fillable = [
        'draft_laporan_id',
        'kepala_uptd_id',
        'status',
        'catatan',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    /**
     * The draft report.
     */
    public function draftLaporan(): BelongsTo
    {
        return $this->belongsTo(DraftLaporan::class, 'draft_laporan_id');
    }

    /**
     * The Kepala UPTD who approved/rejected.
     */
    public function kepalaUptd(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_uptd_id');
    }
}
