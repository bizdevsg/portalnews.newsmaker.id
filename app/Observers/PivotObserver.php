<?php

namespace App\Observers;

use App\Models\Pivot;
use Illuminate\Support\Facades\Artisan;

class PivotObserver
{
    public function saved(Pivot $pivot): void
    {
        $this->refreshCache();
    }

    public function deleted(Pivot $pivot): void
    {
        $this->refreshCache();
    }

    public function restored(Pivot $pivot): void
    {
        $this->refreshCache();
    }

    public function forceDeleted(Pivot $pivot): void
    {
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        Artisan::call('pivot:cache-json');
    }
}
