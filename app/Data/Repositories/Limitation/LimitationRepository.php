<?php

namespace App\Data\Repositories\Limitation;

use App\Data\Entities\Limitation;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;

class LimitationRepository implements ILimitationRepository
{
    use GetAllInQueryCacheStrategy;

    public function __construct(
        private readonly ILimitationRepository     $repository = new MySqlLimitationRepository(),
        private readonly RedisLimitationRepository $redisRepository = new RedisLimitationRepository(),
    )
    {

    }

    public function getOneActiveByDepartmentId(int $departmentId): Limitation|null
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneActiveByDepartmentId',
            'departmentId' => $departmentId,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getOneActiveByDepartmentId($departmentId);
            $this->redisRepository->put($cacheKey, $entity);
        }

        return $entity;
    }

    public function getOneById(int $id): null|Limitation
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneById',
            'id' => $id,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getOneById($id);
            $this->redisRepository->put($cacheKey, $entity);
        }

        return $entity;
    }

    public function create(Limitation $limitation): Limitation
    {
        $limitation = $this->repository->create($limitation);
        $this->redisRepository->clear();
        return $limitation;
    }

    public function update(Limitation $limitation): int
    {
        $result = $this->repository->update($limitation);
        $this->redisRepository->clear();
        return $result;
    }
}
