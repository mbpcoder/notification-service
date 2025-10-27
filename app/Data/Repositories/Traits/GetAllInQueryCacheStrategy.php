<?php

namespace App\Data\Repositories\Traits;

use Illuminate\Support\Collection;

trait GetAllInQueryCacheStrategy
{
    /**
     * @param int $offset
     * @param int $count
     * @return array<int, Collection>
     */
    public function getAll(int $offset = 0, int $count = 10): array
    {
        $cacheKey = $this->redisRepository->makeKey([
            'function_name' => 'getAll',
            'offset' => $offset,
            'count' => $count,
        ]);

        $data = $this->redisRepository->get($cacheKey);

        if ($data === null) {
            [$total, $entities] = $this->repository->getAll($offset, $count);
            $this->redisRepository->put($cacheKey, [$total, $entities]);
        } else {
            [$total, $entities] = $data;
        }
        return [$total, $entities];
    }
}
