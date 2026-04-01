<?php

namespace App\Observers;

use App\Models\EconomicCalendarCategory;
use Illuminate\Support\Facades\Artisan;

class EconomicCalendarCategoryObserver
{
    public function saved(EconomicCalendarCategory $category): void
    {
        $this->refreshCache();
    }

    public function deleted(EconomicCalendarCategory $category): void
    {
        $this->refreshCache();
    }

    public function restored(EconomicCalendarCategory $category): void
    {
        $this->refreshCache();
    }

    public function forceDeleted(EconomicCalendarCategory $category): void
    {
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        Artisan::call('kalender:cache-json');
    }
}
