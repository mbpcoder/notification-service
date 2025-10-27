<?php

namespace App\Data\Repositories\Client;

use App\Data\Entities\Client;
use Illuminate\Support\Collection;

interface IClientRepository
{
    public function getOneActiveByToken(string $token): Client|null;

    public function getOneById(int $id): null|Client;

    public function getAllByIds(array $ids): Collection;

    public function create(Client $client): Client;

    public function update(Client $client): int;

}
