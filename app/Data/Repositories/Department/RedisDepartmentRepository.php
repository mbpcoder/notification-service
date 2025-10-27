<?php

namespace App\Data\Repositories\Department;

use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;
use App\Data\Repositories\RedisRepository;

class RedisDepartmentRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'departments';
        parent::__construct();
    }
}
