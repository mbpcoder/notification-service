<?php

namespace App\Data\Repositories\Client;

use App\Data\Repositories\RedisRepository;
use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;

class RedisClientRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'clients';
        parent::__construct();
    }
}
