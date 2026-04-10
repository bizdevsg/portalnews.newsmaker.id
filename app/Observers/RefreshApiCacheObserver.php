<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class RefreshApiCacheObserver
{
    /**
     * @param  array<int, string>|string  $commands
     */
    public function __construct(
        private readonly array|string $commands
    ) {
    }

    public function saved(Model $model): void
    {
        $this->refreshCache();
    }

    public function deleted(Model $model): void
    {
        $this->refreshCache();
    }

    public function restored(Model $model): void
    {
        $this->refreshCache();
    }

    public function forceDeleted(Model $model): void
    {
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        foreach ((array) $this->commands as $command) {
            Artisan::call($command);
        }
    }
}
