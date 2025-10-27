<?php

namespace App\Data\Repositories\Department;

use App\Data\Entities\Department;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;
use Illuminate\Support\Collection;

class DepartmentRepository implements IDepartmentRepository
{
    use GetAllInQueryCacheStrategy;
    private IDepartmentRepository $repository;
    private RedisDepartmentRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlDepartmentRepository();
        $this->redisRepository = new RedisDepartmentRepository();
    }

    public function getOneById(int $id): null|Department
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

    public function getAllByIds(array $ids): Collection
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getAllByIds',
            'id' => $ids,
        ]);

        $entities = $this->redisRepository->get($cacheKey);

        if ($entities === null) {
            $entities = $this->repository->getAllByIds($ids);
            $this->redisRepository->put($cacheKey, $entities);
        }

        return $entities;
    }

    public function create(Department $department): Department
    {
        $this->redisRepository->clear();

        return $this->repository->create($department);
    }

    public function update(Department $department): int
    {
        $this->redisRepository->clear();

        return $this->repository->update($department);
    }
}
