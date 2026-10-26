<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton record: hanya ada satu baris aktif (yang terakhir dibuat).
 * Setiap upload dari admin meng-update baris itu, bukan menambah baris baru.
 */
class Tiktok extends Model
{
    protected $fillable = [
        'title',
        'video',
    ];

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }
}
