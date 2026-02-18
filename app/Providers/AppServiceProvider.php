<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\EconomicCalendar;
use App\Models\Pivot;
use App\Observers\BeritaObserver;
use App\Observers\EconomicCalendarObserver;
use App\Observers\PivotObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Berita::observe(BeritaObserver::class);
        EconomicCalendar::observe(EconomicCalendarObserver::class);
        Pivot::observe(PivotObserver::class);
    }
}
