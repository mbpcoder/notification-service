<?php

namespace App\Data\Repositories\Line;

use App\Data\Entities\Line;
use App\Data\Repositories\Traits\GetAllInQueryCacheStrategy;
use Illuminate\Support\Collection;

class LineRepository implements ILineRepository
{
    use GetAllInQueryCacheStrategy;
    private ILineRepository $repository;
    private RedisLineRepository $redisRepository;

    public function __construct()
    {
        $this->repository = new MySqlLineRepository();
        $this->redisRepository = new RedisLineRepository();
    }

    public function getOneById(int $id): null|Line
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

    public function create(Line $line): Line
    {
        $result = $this->repository->create($line);

        $this->redisRepository->clear();

        return $result;
    }

    public function update(Line $line): int
    {
        $result = $this->repository->update($line);

        $this->redisRepository->clear();

        return $result;
    }

    public function getLineByProviderIdAndLineNumber(int $providerId, int $number): Line|null
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getLineByProviderIdAndLineNumber',
            'provider_id' => $providerId,
            'number' => $number,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getLineByProviderIdAndLineNumber($providerId, $number);
            $this->redisRepository->put($cacheKey, $entity);
        }
        return $entity;
    }

    public function getDefaultLineByProviderId(int $providerId): Line|null
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getDefaultLineByProviderId',
            'provider_id' => $providerId,
        ]);

        $entity = $this->redisRepository->get($cacheKey);

        if ($entity === null) {
            $entity = $this->repository->getDefaultLineByProviderId($providerId);
            $this->redisRepository->put($cacheKey, $entity);
        }
        return $entity;
    }
}
