<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrasiSample extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'registrasi_sample';

    protected $fillable = [
        'permohonan_id',
        'nomor_registrasi',
        'kode_sample',
        'tanggal_registrasi',
        'status',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_registrasi' => 'datetime',
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
     * User who created the registration.
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Samples under this registration.
     */
    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class, 'registrasi_sample_id');
    }
}
