<?php

namespace App\Data\Repositories\Client;

use App\Data\Entities\Client;
use App\Data\Repositories\Traits\GetAllForGridViewInQueryCacheStrategy;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;
use Illuminate\Support\Collection;

class ClientRepository implements IClientRepository
{
    use GetAllForGridViewInQueryCacheStrategy;
    use GetAllInQueryCacheStrategy;

    private IClientRepository $repository;
    private RedisClientRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlClientRepository();
        $this->redisRepository = new RedisClientRepository();
    }

    public function getOneActiveByToken(string $token): Client|null
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneActiveByToken',
            'token' => $token,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if (is_null($entity)) {
            $entity = $this->repository->getOneActiveByToken($token);
            $this->redisRepository->put($cacheKey, $entity);
        }

        return $entity;
    }

    public function getOneById(int $id): null|Client
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

    public function create(Client $client): Client
    {
        $result = $this->repository->create($client);

        $this->redisRepository->clear();

        return $result;
    }

    public function update(Client $client): int
    {
        $result = $this->repository->update($client);

        $this->redisRepository->clear();

        return $result;
    }
}
