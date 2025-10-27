<?php

namespace App\Data\Repositories\Line;

use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;
use App\Data\Repositories\RedisRepository;

class RedisLineRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'lines';
        parent::__construct();
    }
}
