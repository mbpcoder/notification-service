<?php

namespace App\Data\Repositories\DepartmentUser;

use App\Data\Entities\DepartmentUser;
use Illuminate\Support\Collection;

class DepartmentUserRepository implements IDepartmentUserRepository
{
    private IDepartmentUserRepository $repository;
    private RedisDepartmentUserRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlDepartmentUserRepository();
        $this->redisRepository = new RedisDepartmentUserRepository();
    }

    public function getOneById(int $id): null|DepartmentUser
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

    public function create(DepartmentUser $departmentUser): DepartmentUser
    {
        $this->redisRepository->clear();

        return $this->repository->create($departmentUser);
    }

    public function update(DepartmentUser $departmentUser): int
    {
        $this->redisRepository->clear();

        return $this->repository->update($departmentUser);
    }
}
