<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiQc extends Model
{
    use HasUuids;

    protected $table = 'verifikasi_qc';

    protected $fillable = [
        'hasil_uji_id',
        'qc_id',
        'status',
        'catatan',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'diverifikasi_pada' => 'datetime',
        ];
    }

    /**
     * The test result being verified.
     */
    public function hasilUji(): BelongsTo
    {
        return $this->belongsTo(HasilUji::class, 'hasil_uji_id');
    }

    /**
     * The QC officer.
     */
    public function qcOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qc_id');
    }
}
