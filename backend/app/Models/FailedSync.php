<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FailedSync extends Model
{
    use HasUuids;

    protected $table = 'failed_syncs';

    protected $fillable = [
        'user_id',
        'reference_id',
        'payload',
        'error_message',
        'retry_count',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'retry_count' => 'integer',
        ];
    }

    /**
     * The user who had the sync failure.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
