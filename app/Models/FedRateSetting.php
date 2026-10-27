<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton record: hanya ada satu baris pengaturan (yang terakhir dibuat).
 */
class FedRateSetting extends Model
{
    protected $fillable = [
        'current_target_lower',
        'current_target_upper',
        'source_updated_at',
    ];

    protected $casts = [
        'current_target_lower' => 'float',
        'current_target_upper' => 'float',
        'source_updated_at' => 'datetime',
    ];

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }
}
