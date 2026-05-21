<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipLaporan extends Model
{
    use HasUuids;

    protected $table = 'arsip_laporan';

    public $timestamps = false;

    protected $fillable = [
        'laporan_final_id',
        'archived_at',
        'archived_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'archived_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * The final report.
     */
    public function laporanFinal(): BelongsTo
    {
        return $this->belongsTo(LaporanFinal::class, 'laporan_final_id');
    }

    /**
     * User who archived.
     */
    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
