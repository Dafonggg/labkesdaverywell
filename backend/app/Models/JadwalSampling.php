<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalSampling extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'jadwal_sampling';

    protected $fillable = [
        'permohonan_id',
        'petugas_lapangan_id',
        'tanggal_sampling',
        'jam_sampling',
        'lokasi',
        'latitude',
        'longitude',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sampling' => 'date',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'deleted_at' => 'datetime',
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
     * The assigned field officer.
     */
    public function petugasLapangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_lapangan_id');
    }

    /**
     * Samples collected during this schedule.
     */
    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class, 'jadwal_sampling_id');
    }
}
