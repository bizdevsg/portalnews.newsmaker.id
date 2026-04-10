<?php

namespace App\Observers;

class PopupBannerCacheObserver extends RefreshApiCacheObserver
{
    public function __construct()
    {
        parent::__construct('popup-banner:cache-json');
    }
}
