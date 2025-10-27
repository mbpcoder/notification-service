<?php

namespace App\Data\Repositories\Credential;

use App\Data\Entities\Credential;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;
use Illuminate\Support\Collection;

class CredentialRepository implements ICredentialRepository
{

    use GetAllInQueryCacheStrategy;

    private ICredentialRepository $repository;
    private RedisCredentialRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlCredentialRepository();
        $this->redisRepository = new RedisCredentialRepository();
    }

    public function getOneById(int $id): null|Credential
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

    public function getAllByProviderIdsAndLineIds(array $providerIds, array $lineIds): Collection
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getAllByIds',
            'providerIds' => $providerIds,
            'lineIds' => $lineIds,
        ]);

        $entities = $this->redisRepository->get($cacheKey);

        if ($entities === null) {
            $entities = $this->repository->getAllByProviderIdsAndLineIds($providerIds, $lineIds);
            $this->redisRepository->put($cacheKey, $entities);
        }

        return $entities;
    }

    public function create(Credential $credential): Credential
    {
        $this->redisRepository->clear();

        return $this->repository->create($credential);
    }

    public function update(Credential $credential): int
    {
        $this->redisRepository->clear();

        return $this->repository->update($credential);
    }

    public function remove(Credential $credential): int
    {
        $this->redisRepository->clear();

        return $this->repository->remove($credential);
    }

    public function restore(Credential $credential): int
    {
        $this->redisRepository->clear();

        return $this->repository->restore($credential);
    }
}
