<?php

namespace App\Observers;

class IklanCacheObserver extends RefreshApiCacheObserver
{
    public function __construct()
    {
        parent::__construct('iklan:cache-json');
    }
}

