<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role?->code,
        ];
    }

    /**
     * The role this user belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionCode): bool
    {
        return $this->role->permissions()->where('code', $permissionCode)->exists();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleCode): bool
    {
        return $this->role?->code === $roleCode;
    }

    /**
     * Permohonan created by this user.
     */
    public function permohonan(): HasMany
    {
        return $this->hasMany(PermohonanPengujian::class, 'dibuat_oleh');
    }

    /**
     * Notifications for this user.
     */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
