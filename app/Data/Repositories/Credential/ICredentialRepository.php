<?php

namespace App\Data\Repositories\Credential;

use App\Data\Entities\Credential;
use Illuminate\Support\Collection;

interface ICredentialRepository
{
    public function getOneById(int $id): null|Credential;

    public function getAllByIds(array $ids): Collection;

    public function getAllByProviderIdsAndLineIds(array $providerIds, array $lineIds): Collection;

    public function create(Credential $credential): Credential;

    public function update(Credential $credential): int;

    public function remove(Credential $credential): int;

    public function restore(Credential $credential): int;

}
