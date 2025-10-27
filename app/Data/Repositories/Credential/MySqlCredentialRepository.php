<?php

namespace App\Data\Repositories\Credential;

use App\Data\Entities\Credential;
use App\Data\Enums\CredentialEntityEnum;
use App\Data\Factories\CredentialFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlCredentialRepository extends MySqlRepository implements ICredentialRepository
{
    public function __construct()
    {
        $this->table = 'credentials';
        $this->primaryKey = 'id';
        $this->softDelete = true;
        $this->factory = new CredentialFactory();

        parent::__construct();
    }

    public function getOneById(int $id): null|Credential
    {
        $credential = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $credential ? $this->factory->makeEntityFromStdClass($credential) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $credential = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($credential);
    }

    public function getAllByProviderIdsAndLineIds(array $providerIds, array $lineIds): Collection
    {
        $entities = $this->newQuery()
            ->where(function ($query) use ($providerIds) {
                $query->where('entity', CredentialEntityEnum::PROVIDER->value)
                    ->whereIn('entity_id', $providerIds);
            })
            ->orWhere(function ($query) use ($lineIds) {
                $query->where('entity', CredentialEntityEnum::LINE->value)
                    ->whereIn('entity_id', $lineIds);
            })
            ->get();

        return $this->factory->makeCollectionOfEntities($entities);
    }


    public function create(Credential $credential): Credential
    {
        $credential->createdAt = $this->now();
        $credential->updatedAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'entity' => $credential->entity->value,
                'entity_id' => $credential->entityId,
                'username' => encrypt($credential->username),
                'password' => encrypt($credential->password),
                'token' => encrypt($credential->token),
                'is_active' => $credential->isActive,
                'created_at' => $credential->createdAt,
                'updated_at' => $credential->updatedAt,
            ]);

        $credential->id = $id;

        return $credential;
    }

    public function update(Credential $credential): int
    {
        $credential->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $credential->id)
            ->update([
                'entity' => $credential->entity->value,
                'entity_id' => $credential->entityId,
                'username' => encrypt($credential->username),
                'password' => encrypt($credential->password),
                'token' => encrypt($credential->token),
                'is_active' => $credential->isActive,
                'updated_at' => $credential->updatedAt,
            ]);
    }

    public function remove(Credential $credential): int
    {
        return $this->newQuery()
            ->where($this->primaryKey, $credential->id)
            ->update([
                'deleted_at' => $this->now(),
            ]);
    }

    public function restore(Credential $credential): int
    {
        return $this->newQuery()
            ->where($this->primaryKey, $credential->id)
            ->update([
                'deleted_at' => null,
            ]);
    }
}
