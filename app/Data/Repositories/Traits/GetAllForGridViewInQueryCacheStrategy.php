<?php

namespace App\Data\Repositories\Traits;

trait GetAllForGridViewInQueryCacheStrategy
{
    public function getAllForGridView(int $offset = 0, int $count = 0, array|null $orders = null, array|null $filters = null): array
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getAll',
            'offset' => $offset,
            'count' => $count,
            'orders' => $orders,
            'filters' => $filters,
        ]);
        [$entities, $total] = $this->redisRepository->get($cacheKey);

        if ($entities === null) {
            [$entities, $total] = $this->repository->getAllForGridView($offset, $count, $orders, $filters);
            $this->redisRepository->put($cacheKey, [$entities, $total]);
        }

        return [$entities, $total];
    }
}
