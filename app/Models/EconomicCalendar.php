<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EconomicCalendar extends Model
{
    use HasFactory;

    protected $table = 'economic_calendars';

    protected $fillable = [
        'economic_calendar_category_id',
        'date',
        'time',
        'country',
        'impact',
        'figures',
        'previous',
        'forecast',
        'actual',
        'sources',
        'measures',
        'usual_effect',
        'frequency',
        'next_released',
        'notes',
        'isBankHoliday',
        'bankHolidayNote',
        'why_trader_care',
    ];

    protected $casts = [
        'date' => 'date',
        'isBankHoliday' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EconomicCalendarCategory::class, 'economic_calendar_category_id');
    }
}
