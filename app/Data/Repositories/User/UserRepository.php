<?php

namespace App\Data\Repositories\User;

use App\Data\Entities\User;
use App\Data\Repositories\Traits\GetAllForGridViewInQueryCacheStrategy;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;
use Illuminate\Support\Collection;

class UserRepository implements IUserRepository
{
    use GetAllForGridViewInQueryCacheStrategy;
    use GetAllInQueryCacheStrategy;

    private IUserRepository $repository;
    private RedisUserRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlUserRepository();
        $this->redisRepository = new RedisUserRepository();
    }

    public function getOneById(int $id): null|User
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

    public function getOneByRememberToken(string $token): null|User
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneByRememberToken',
            'token' => $token,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getOneByRememberToken($token);
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

    public function getOneByEmail(string $email): null|User
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneByEmail',
            'email' => $email,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getOneByEmail($email);
            $this->redisRepository->put($cacheKey, $entity);
        }

        return $entity;
    }

    public function create(User $user): User
    {
        $this->redisRepository->clear();

        return $this->repository->create($user);
    }

    public function update(User $user): int
    {
        $this->redisRepository->clear();

        return $this->repository->update($user);
    }
}
