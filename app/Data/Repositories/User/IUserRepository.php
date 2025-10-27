<?php

namespace App\Data\Repositories\User;

use App\Data\Entities\User;
use Illuminate\Support\Collection;

interface IUserRepository
{
    public function getOneById(int $id): null|User;

    public function getOneByRememberToken(string $token): null|User;

    public function getAllByIds(array $ids): Collection;

    public function getOneByEmail(string $email): null|User;

    public function create(User $user): User;

    public function update(User $user): int;

}
