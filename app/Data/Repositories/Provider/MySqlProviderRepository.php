<?php

namespace App\Data\Repositories\Provider;

use App\Data\Entities\Provider;
use App\Data\Factories\ProviderFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlProviderRepository extends MySqlRepository implements IProviderRepository
{
    public function __construct()
    {
        $this->table = 'providers';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new ProviderFactory();

        parent::__construct();
    }

    public function getOneById(int $id): null|Provider
    {
        $provider = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $provider ? $this->factory->makeEntityFromStdClass($provider) : null;
    }

    public function getOneBySlug(string $slug): null|Provider
    {
        $provider = $this->newQuery()
            ->where('slug', $slug)
            ->first();

        return $provider ? $this->factory->makeEntityFromStdClass($provider) : null;
    }

    public function getOneDefault(): null|Provider
    {
        $provider = $this->newQuery()
            ->where('is_default', true)
            ->first();

        return $provider ? $this->factory->makeEntityFromStdClass($provider) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $provider = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($provider);
    }

    public function create(Provider $provider): Provider
    {
        $now = $this->now();
        $provider->createdAt = $now;
        $provider->updatedAt = $now;

        $id = $this->newQuery()
            ->insertGetId([
                'name' => $provider->name,
                'class_name' => $provider->className,
                'slug' => $provider->slug,
                'url' => $provider->url,
                'is_active' => $provider->isActive ?? false,
                'is_default' => $provider->isDefault ?? false,
                'created_at' => $provider->createdAt,
                'updated_at' => $provider->updatedAt,
            ]);

        $provider->id = $id;

        return $provider;
    }

    public function update(Provider $provider): int
    {
        $provider->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $provider->id)
            ->update([
                'name' => $provider->name,
                'class_name' => $provider->className,
                'slug' => $provider->slug,
                'url' => $provider->url,
                'is_active' => $provider->isActive,
                'is_default' => $provider->isDefault,
                'updated_at' => $provider->updatedAt,
            ]);
    }
}
