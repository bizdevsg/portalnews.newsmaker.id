<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use App\Models\Pivot;
use App\Models\PopupBanner;
use App\Models\Iklan;
use App\Models\Tiktok;
use App\Observers\BeritaObserver;
use App\Observers\NewsmakerCacheObserver;
use App\Observers\PasarIndonesiaCacheObserver;
use App\Observers\PasarIndonesiaRegulasiCacheObserver;
use App\Observers\PopupBannerCacheObserver;
use App\Observers\IklanCacheObserver;
use App\Observers\PivotObserver;
use App\Observers\TiktokCacheObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        Pivot::observe(PivotObserver::class);
        NewsmakerArticle::observe(NewsmakerCacheObserver::class);
        NewsmakerMainCategory::observe(NewsmakerCacheObserver::class);
        NewsmakerSubCategory::observe(NewsmakerCacheObserver::class);
        PasarIndonesiaArticle::observe(PasarIndonesiaCacheObserver::class);
        PasarIndonesiaCategory::observe(PasarIndonesiaCacheObserver::class);
        PasarIndonesiaRegulasiInstitusiArticle::observe(PasarIndonesiaRegulasiCacheObserver::class);
        PasarIndonesiaRegulasiInstitusiCategory::observe(PasarIndonesiaRegulasiCacheObserver::class);
        Tiktok::observe(TiktokCacheObserver::class);
        PopupBanner::observe(PopupBannerCacheObserver::class);
        Iklan::observe(IklanCacheObserver::class);
        URL::forceScheme('https');
        URL::forceRootUrl(config('app.url'));
    }
}
