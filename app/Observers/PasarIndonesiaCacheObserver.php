<?php

namespace App\Observers;

class PasarIndonesiaCacheObserver extends RefreshApiCacheObserver
{
    public function __construct()
    {
        parent::__construct('pasar-indonesia:cache-json');
    }
}
