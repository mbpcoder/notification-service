<?php

namespace App\Data\Repositories\Client;

use App\Data\Entities\Client;
use App\Data\Factories\ClientFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlClientRepository extends MySqlRepository implements IClientRepository
{
    public function __construct()
    {
        $this->table = 'clients';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new ClientFactory();

        parent::__construct();
    }

    public function getOneActiveByToken(string $token): null|Client
    {
        $nowString = $this->now();

        $client = $this->newQuery()
            ->where('token', $token)
            ->where('is_active', true)
            ->where(function ($query) use ($nowString) {
                $query->where('expired_at', '>', $nowString)
                    ->orWhereNull('expired_at');
            })
            ->first();

        return $client ? $this->factory->makeEntityFromStdClass($client) : null;
    }

    public function getOneById(int $id): null|Client
    {
        $client = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $client ? $this->factory->makeEntityFromStdClass($client) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $client = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($client);
    }

    public function create(Client $client): Client
    {
        $client->createdAt = $client->updatedAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'department_id' => $client->departmentId,
                'name' => $client->name,
                'token' => $client->token,
                'is_active' => $client->isActive,
                'expired_at' => $client->expiredAt,
                'created_at' => $client->createdAt,
                'updated_at' => $client->updatedAt
            ]);

        $client->id = $id;

        return $client;
    }

    public function update(Client $client): int
    {
        $client->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $client->getPrimaryKey())
            ->update([
                'department_id' => $client->departmentId,
                'name' => $client->name,
                'token' => $client->token,
                'is_active' => $client->isActive,
                'expired_at' => $client->expiredAt,
                'updated_at' => $client->updatedAt
            ]);
    }
}
