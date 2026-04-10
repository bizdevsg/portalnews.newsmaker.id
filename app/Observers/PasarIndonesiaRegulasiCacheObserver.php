<?php

namespace App\Observers;

class PasarIndonesiaRegulasiCacheObserver extends RefreshApiCacheObserver
{
    public function __construct()
    {
        parent::__construct('pasar-indonesia-regulasi:cache-json');
    }
}
