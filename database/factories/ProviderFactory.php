<?php

namespace Database\Factories;

use App\Data\Entities\Provider;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProviderFactory
{
    /**
     * Create a new provider instance with fake data.
     *
     * @param Faker $faker
     * @return Provider
     */
    public function definition(Faker $faker): Provider
    {
        $name = $faker->company;
        $provider = new Provider();
        $provider->name = $name;
        $provider->className = 'App\\Services\\' . Str::studly($name);
        $provider->slug = Str::slug($name);
        $provider->url = $faker->url;
        $provider->isActive = $faker->boolean(70); // 70% chance of being active
        $provider->isDefault = $faker->boolean(20); // 20% chance of being default
        $provider->createdAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $provider->updatedAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');

        return $provider;
    }

    /**
     * Insert a provider into the database using query builder.
     *
     * @param Provider $provider
     * @return int The inserted provider's ID
     */
    public function create(Provider $provider): int
    {
        return DB::table('providers')->insertGetId([
            'name' => $provider->name,
            'class_name' => $provider->className,
            'slug' => $provider->slug,
            'url' => $provider->url,
            'is_active' => $provider->isActive,
            'is_default' => $provider->isDefault,
            'created_at' => $provider->createdAt,
            'updated_at' => $provider->updatedAt,
        ]);
    }

    /**
     * Create multiple providers.
     *
     * @param int $count
     * @param Faker|null $faker
     * @return array List of inserted provider IDs
     */
    public function createMany(int $count, ?Faker $faker = null): array
    {
        $faker = $faker ?? app(Faker::class);
        $ids = [];

        for ($i = 0; $i < $count; $i++) {
            $provider = $this->definition($faker);
            $ids[] = $this->create($provider);
        }

        return $ids;
    }
}
