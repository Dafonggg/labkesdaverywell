<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasUuids;

    protected $table = 'notifications';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    /**
     * The user this notification belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
