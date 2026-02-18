<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\EconomicCalendar;
use App\Models\Pivot;
use App\Observers\BeritaCacheObserver;
use App\Observers\EconomicCalendarCacheObserver;
use App\Observers\PivotCacheObserver;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        Berita::observe(BeritaCacheObserver::class);
        EconomicCalendar::observe(EconomicCalendarCacheObserver::class);
        Pivot::observe(PivotCacheObserver::class);
    }
}
