<?php

namespace App\Data\Repositories\Credential;

use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;
use App\Data\Repositories\RedisRepository;

class RedisCredentialRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'credentials';
        parent::__construct();
    }
}
