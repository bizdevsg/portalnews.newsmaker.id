<?php

namespace App\Observers;

use App\Models\Berita;
use Illuminate\Support\Facades\Artisan;

class BeritaObserver
{
    public function saved(Berita $berita): void
    {
        $this->refreshCache();
    }

    public function deleted(Berita $berita): void
    {
        $this->refreshCache();
    }

    public function restored(Berita $berita): void
    {
        $this->refreshCache();
    }

    public function forceDeleted(Berita $berita): void
    {
        $this->refreshCache();
    }

    private function refreshCache(): void
    {
        Artisan::call('berita:cache-json');
    }
}
