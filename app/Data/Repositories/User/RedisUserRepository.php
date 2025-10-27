<?php

namespace App\Data\Repositories\User;

use App\Data\Repositories\RedisRepository;
use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;

class RedisUserRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'users';
        parent::__construct();
    }
}
