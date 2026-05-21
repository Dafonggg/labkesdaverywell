<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanPengujian extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'permohonan_pengujian';

    protected $fillable = [
        'nomor_permohonan',
        'jenis_pemohon',
        'nama_instansi',
        'nama_pemohon',
        'email',
        'phone',
        'alamat',
        'jenis_sample',
        'total_biaya',
        'status',
        'catatan',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'total_biaya' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * User who created this request.
     */
    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Payments for this request.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'permohonan_id');
    }

    /**
     * Sampling schedules.
     */
    public function jadwalSampling(): HasMany
    {
        return $this->hasMany(JadwalSampling::class, 'permohonan_id');
    }

    /**
     * Sample registrations.
     */
    public function registrasiSample(): HasMany
    {
        return $this->hasMany(RegistrasiSample::class, 'permohonan_id');
    }

    /**
     * Draft reports.
     */
    public function draftLaporan(): HasMany
    {
        return $this->hasMany(DraftLaporan::class, 'permohonan_id');
    }
}
