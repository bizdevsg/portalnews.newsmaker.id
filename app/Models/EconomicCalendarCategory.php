<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EconomicCalendarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'impact',
        'figures',
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
        'isBankHoliday' => 'boolean',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(EconomicCalendar::class, 'economic_calendar_category_id');
    }

    public function latestDetail(): HasOne
    {
        return $this->hasOne(EconomicCalendar::class, 'economic_calendar_category_id')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->orderByDesc('id');
    }

    public function syncedDetailAttributes(): array
    {
        return [
            'country' => $this->country,
            'impact' => $this->impact,
            'figures' => $this->figures,
            'sources' => $this->sources,
            'measures' => $this->measures,
            'usual_effect' => $this->usual_effect,
            'frequency' => $this->frequency,
            'next_released' => $this->next_released,
            'notes' => $this->notes,
            'isBankHoliday' => $this->isBankHoliday,
            'bankHolidayNote' => $this->bankHolidayNote,
            'why_trader_care' => $this->why_trader_care,
        ];
    }
}
