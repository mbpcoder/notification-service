<?php

namespace App\Data\Repositories\Limitation;

use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;
use App\Data\Repositories\RedisRepository;

class RedisLimitationRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'limitations';
        parent::__construct();
    }
}
