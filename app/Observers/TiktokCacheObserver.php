<?php

namespace App\Observers;

class TiktokCacheObserver extends RefreshApiCacheObserver
{
    public function __construct()
    {
        parent::__construct('tiktok:cache-json');
    }
}
