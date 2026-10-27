<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FedRateMeeting extends Model
{
    protected $fillable = [
        'meeting_date',
        'meeting_time',
        'future_price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'future_price' => 'float',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function rows(): HasMany
    {
        return $this->hasMany(FedRateMeetingRow::class)->orderBy('target_lower');
    }
}
