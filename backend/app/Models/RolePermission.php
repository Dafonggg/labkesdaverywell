<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission extends Pivot
{
    use HasUuids;

    protected $table = 'role_permissions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * The role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * The permission.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
