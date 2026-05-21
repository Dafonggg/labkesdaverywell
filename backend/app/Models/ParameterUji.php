<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParameterUji extends Model
{
    use HasUuids;

    protected $table = 'parameter_uji';

    protected $fillable = [
        'nama_parameter',
        'satuan',
        'baku_mutu_min',
        'baku_mutu_max',
        'metode_uji',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'baku_mutu_min' => 'decimal:4',
            'baku_mutu_max' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Test results using this parameter.
     */
    public function hasilUji(): HasMany
    {
        return $this->hasMany(HasilUji::class, 'parameter_uji_id');
    }
}
