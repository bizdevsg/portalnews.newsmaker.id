<?php

namespace App\Observers;

use App\Models\Berita;
use Illuminate\Support\Facades\Artisan;

class BeritaCacheObserver
{
    public function saved(Berita $berita): void
    {
        Artisan::call('berita:cache-json');
    }

    public function deleted(Berita $berita): void
    {
        Artisan::call('berita:cache-json');
    }

    public function restored(Berita $berita): void
    {
        Artisan::call('berita:cache-json');
    }
}
