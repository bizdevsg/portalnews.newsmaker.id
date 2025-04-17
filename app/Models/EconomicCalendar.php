<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EconomicCalendar extends Model
{
    use HasFactory;

    protected $table = 'economic_calendars';

    protected $fillable = [
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
        'why_trader_care',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
