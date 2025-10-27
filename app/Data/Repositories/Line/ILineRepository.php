<?php

namespace App\Data\Repositories\Line;

use App\Data\Entities\Line;
use Illuminate\Support\Collection;

interface ILineRepository
{
    public function getOneById(int $id): null|Line;

    public function getAllByIds(array $ids): Collection;

    public function create(Line $line): Line;

    public function update(Line $line): int;

    public function getLineByProviderIdAndLineNumber(int $providerId, int $number): Line|null;

    public function getDefaultLineByProviderId(int $providerId): Line|null;

}
