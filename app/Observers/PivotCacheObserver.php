<?php

namespace App\Observers;

use App\Models\Pivot;
use Illuminate\Support\Facades\Artisan;

class PivotCacheObserver
{
    public function saved(Pivot $pivot): void
    {
        Artisan::call('pivot:cache-json');
    }

    public function deleted(Pivot $pivot): void
    {
        Artisan::call('pivot:cache-json');
    }

    public function restored(Pivot $pivot): void
    {
        Artisan::call('pivot:cache-json');
    }
}
