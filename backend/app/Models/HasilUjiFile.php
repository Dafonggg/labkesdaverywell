<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilUjiFile extends Model
{
    use HasUuids;

    protected $table = 'hasil_uji_files';

    public $timestamps = false;

    protected $fillable = [
        'hasil_uji_id',
        'file_name',
        'file_path',
        'file_type',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * The parent test result.
     */
    public function hasilUji(): BelongsTo
    {
        return $this->belongsTo(HasilUji::class, 'hasil_uji_id');
    }
}
