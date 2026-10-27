<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Berbeda dari modul lain (yang pakai JSON file cache), Fed Rate Monitor
 * pakai Cache::remember 5 menit karena filter "meeting_date >= hari ini"
 * harus dihitung ulang tiap request. Observer ini cuma membersihkan cache
 * itu supaya perubahan admin langsung tampil, bukan menunggu 5 menit.
 */
class FedRateMonitorCacheObserver
{
    public const CACHE_KEY = 'fed-rate-monitor:api';

    public function saved(Model $model): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function deleted(Model $model): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function restored(Model $model): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function forceDeleted(Model $model): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
