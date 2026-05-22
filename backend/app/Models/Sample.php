<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sample extends Model
{
    use HasUuids;

    protected $table = 'samples';

    protected $fillable = [
        'registrasi_sample_id',
        'jadwal_sampling_id',
        'jenis_sample',
        'kondisi_sample',
        'metode_pengambilan',
        'suhu',
        'cuaca',
        'lokasi_pengambilan',
        'latitude',
        'longitude',
        'waktu_pengambilan',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'waktu_pengambilan' => 'datetime',
        ];
    }

    /**
     * The sample registration.
     */
    public function registrasiSample(): BelongsTo
    {
        return $this->belongsTo(RegistrasiSample::class, 'registrasi_sample_id');
    }

    /**
     * The sampling schedule.
     */
    public function jadwalSampling(): BelongsTo
    {
        return $this->belongsTo(JadwalSampling::class, 'jadwal_sampling_id');
    }

    /**
     * Files attached to this sample.
     */
    public function files(): HasMany
    {
        return $this->hasMany(SampleFile::class, 'sample_id');
    }

    /**
     * Test results for this sample.
     */
    public function hasilUji(): HasMany
    {
        return $this->hasMany(HasilUji::class, 'sample_id');
    }
}
