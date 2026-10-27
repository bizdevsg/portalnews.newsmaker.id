<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FedRateMeetingRow extends Model
{
    protected $fillable = [
        'fed_rate_meeting_id',
        'target_lower',
        'target_upper',
        'current_probability',
        'previous_day_probability',
        'previous_week_probability',
    ];

    protected $casts = [
        'target_lower' => 'float',
        'target_upper' => 'float',
        'current_probability' => 'float',
        'previous_day_probability' => 'float',
        'previous_week_probability' => 'float',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(FedRateMeeting::class, 'fed_rate_meeting_id');
    }
}
