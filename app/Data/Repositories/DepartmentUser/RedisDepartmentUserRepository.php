<?php

namespace App\Data\Repositories\DepartmentUser;

use App\Data\Repositories\CacheStrategies\QueryCacheStrategy;
use App\Data\Repositories\RedisRepository;

class RedisDepartmentUserRepository extends RedisRepository
{
    use QueryCacheStrategy;

    public function __construct()
    {
        $this->cacheTag = 'department_user';
        parent::__construct();
    }
}
