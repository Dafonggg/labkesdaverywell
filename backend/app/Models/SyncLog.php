<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncLog extends Model
{
    use HasUuids;

    protected $table = 'sync_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sync_type',
        'status',
        'total_data',
        'success_data',
        'failed_data',
        'response_message',
        'synced_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'total_data' => 'integer',
            'success_data' => 'integer',
            'failed_data' => 'integer',
            'synced_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * The user who performed the sync.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
