<?php

namespace App\Data\Repositories\User;

use App\Data\Entities\User;
use App\Data\Factories\UserFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlUserRepository extends MySqlRepository implements IUserRepository
{
    public function __construct()
    {
        $this->table = 'users';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new UserFactory();

        parent::__construct();
    }

    public function getOneById(int $id): null|User
    {
        $user = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $user ? $this->factory->makeEntityFromStdClass($user) : null;
    }

    public function getOneByRememberToken(string $token): null|User
    {
        $user = $this->newQuery()
            ->where('remember_token', $token)
            ->first();

        return $user ? $this->factory->makeEntityFromStdClass($user) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $user = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($user);
    }

    public function getOneByEmail(string $email): null|User
    {
        $user = $this->newQuery()
            ->where('email', $email)
            ->first();

        return $user ? $this->factory->makeEntityFromStdClass($user) : null;
    }

    public function create(User $user): User
    {
        $now = $this->now();
        $user->createdAt = $now;
        $user->updatedAt = $now;

        $id = $this->newQuery()
            ->insertGetId([
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->emailVerifiedAt,
                'password' => $user->password,
                'remember_token' => $user->rememberToken,
                'created_at' => $user->createdAt,
                'updated_at' => $user->updatedAt,
            ]);

        $user->id = $id;

        return $user;
    }

    public function update(User $user): int
    {
        $user->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $user->getPrimaryKey())
            ->update([
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->emailVerifiedAt,
                'password' => $user->password,
                'remember_token' => $user->rememberToken,
                'updated_at' => $user->updatedAt,
            ]);
    }
}
