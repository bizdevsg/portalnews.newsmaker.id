<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    use HasFactory;

    protected $table = 'historical_data';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'date',
        'symbol',
        'event',
        'open',
        'high',
        'low',
        'close',
        'change',
        'volume',
        'openInterest',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $hidden = [
        'date',
        'symbol',
        'event',
        'change',
        'openInterest',
        'createdAt',
        'updatedAt',
    ];

    protected $appends = [
        'tanggal',
        'category',
        'description',
        'isBankHoliday',
        'chg',
        'open_interest',
        'created_at',
        'updated_at',
    ];

    public function getTanggalAttribute()
    {
        return $this->date;
    }

    public function getCategoryAttribute()
    {
        return $this->symbol;
    }

    public function getDescriptionAttribute()
    {
        return $this->event;
    }

    public function getIsBankHolidayAttribute(): bool
    {
        return !empty($this->event)
            && $this->open === null
            && $this->high === null
            && $this->low === null
            && $this->close === null;
    }

    public function getChgAttribute()
    {
        return $this->change;
    }

    public function getOpenInterestAttribute()
    {
        return $this->openInterest;
    }

    public function getCreatedAtAttribute()
    {
        return $this->getAttribute('createdAt');
    }

    public function getUpdatedAtAttribute()
    {
        return $this->getAttribute('updatedAt');
    }
}
