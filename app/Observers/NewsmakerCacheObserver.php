<?php

namespace App\Observers;

class NewsmakerCacheObserver extends RefreshApiCacheObserver
{
    public function __construct()
    {
        parent::__construct('newsmaker:cache-json');
    }
}
