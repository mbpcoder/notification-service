<?php

namespace App\Data\Repositories\Provider;

use App\Data\Entities\Provider;
use Illuminate\Support\Collection;

interface IProviderRepository
{

    public function getOneById(int $id): null|Provider;

    public function getOneBySlug(string $slug): null|Provider;

    public function getOneDefault(): null|Provider;

    public function getAllByIds(array $ids): Collection;

    public function create(Provider $provider): Provider;

    public function update(Provider $provider): int;

}
