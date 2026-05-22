<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HasilUji extends Model
{
    use HasUuids;

    protected $table = 'hasil_uji';

    protected $fillable = [
        'sample_id',
        'parameter_uji_id',
        'analis_id',
        'nilai_hasil',
        'status',
        'status_hasil',
        'metode_pengujian',
        'catatan',
        'status_qc',
        'diuji_pada',
    ];

    protected function casts(): array
    {
        return [
            'nilai_hasil' => 'decimal:4',
            'diuji_pada' => 'datetime',
        ];
    }

    /**
     * The sample being tested.
     */
    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }

    /**
     * The test parameter.
     */
    public function parameterUji(): BelongsTo
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id');
    }

    /**
     * The analyst who conducted the test.
     */
    public function analis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analis_id');
    }

    /**
     * Files attached to this test result.
     */
    public function files(): HasMany
    {
        return $this->hasMany(HasilUjiFile::class, 'hasil_uji_id');
    }

    /**
     * QC verifications for this result.
     */
    public function verifikasiQc(): HasMany
    {
        return $this->hasMany(VerifikasiQc::class, 'hasil_uji_id');
    }
}
