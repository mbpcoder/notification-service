<?php

namespace App\Data\Repositories\Provider;

use App\Data\Repositories\RedisRepository;
use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;

class RedisProviderRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'providers';
        parent::__construct();
    }
}
