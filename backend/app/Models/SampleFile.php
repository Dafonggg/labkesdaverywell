<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleFile extends Model
{
    use HasUuids;

    protected $table = 'sample_files';

    public $timestamps = false;

    protected $fillable = [
        'sample_id',
        'file_name',
        'file_path',
        'file_type',
        'uploaded_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * The parent sample.
     */
    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }

    /**
     * User who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
