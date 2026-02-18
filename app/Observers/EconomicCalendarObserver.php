<?php

namespace App\Observers;

use App\Models\EconomicCalendar;
use Illuminate\Support\Facades\Artisan;

class EconomicCalendarObserver
{
    public function saved(EconomicCalendar $calendar): void
    {
        $this->refreshCache();
    }

    public function deleted(EconomicCalendar $calendar): void
    {
        $this->refreshCache();
    }

    public function restored(EconomicCalendar $calendar): void
    {
        $this->refreshCache();
    }

    public function forceDeleted(EconomicCalendar $calendar): void
    {
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        Artisan::call('kalender:cache-json');
    }
}
