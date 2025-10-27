<?php

namespace App\Data\Repositories\Provider;

use App\Data\Entities\Provider;
use App\Data\Repositories\Traits\GetAllForGridViewInQueryCacheStrategy;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;
use Illuminate\Support\Collection;

class ProviderRepository implements IProviderRepository
{
    use GetAllForGridViewInQueryCacheStrategy;
    use GetAllInQueryCacheStrategy;

    private IProviderRepository $repository;
    private RedisProviderRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlProviderRepository();
        $this->redisRepository = new RedisProviderRepository();
    }

    public function getOneById(int $id): null|Provider
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

    public function getOneBySlug(string $slug): null|Provider
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneBySlug',
            'slug' => $slug,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getOneBySlug($slug);
            $this->redisRepository->put($cacheKey, $entity);
        }

        return $entity;
    }

    public function getOneDefault(): null|Provider
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getOneDefault'
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getOneDefault();
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

    public function create(Provider $provider): Provider
    {
        $result = $this->repository->create($provider);

        $this->redisRepository->clear();

        return $result;
    }

    public function update(Provider $provider): int
    {
        $result = $this->repository->update($provider);

        $this->redisRepository->clear();

        return $result;
    }
}
