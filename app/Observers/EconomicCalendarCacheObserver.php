<?php

namespace App\Observers;

use App\Models\EconomicCalendar;
use Illuminate\Support\Facades\Artisan;

class EconomicCalendarCacheObserver
{
    public function saved(EconomicCalendar $calendar): void
    {
        Artisan::call('kalender:cache-json');
    }

    public function deleted(EconomicCalendar $calendar): void
    {
        Artisan::call('kalender:cache-json');
    }

    public function restored(EconomicCalendar $calendar): void
    {
        Artisan::call('kalender:cache-json');
    }
}
