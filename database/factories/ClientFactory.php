<?php

namespace Database\Factories;

use App\Data\Entities\Client;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientFactory
{
    /**
     * Create a new client instance with fake data.
     *
     * @param Faker $faker
     * @return Client
     */
    public function definition(Faker $faker): Client
    {
        $client = new Client();
        $client->departmentId = $faker->numberBetween(1, 10); // Assuming departments exist with IDs 1-10
        $client->name = $faker->company;
        $client->token = Str::random(32); // Generate a random 32-character token
        $client->isActive = $faker->boolean(80); // 80% chance of being active
        $client->expiredAt = $faker->optional(0.3)->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s');
        $client->createdAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $client->updatedAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');

        return $client;
    }

    /**
     * Insert a client into the database using query builder.
     *
     * @param Client $client
     * @return int The inserted client's ID
     */
    public function create(Client $client): int
    {
        return DB::table('clients')->insertGetId([
            'department_id' => $client->departmentId,
            'name' => $client->name,
            'token' => $client->token,
            'is_active' => $client->isActive,
            'expired_at' => $client->expiredAt,
            'created_at' => $client->createdAt,
            'updated_at' => $client->updatedAt,
        ]);
    }

    /**
     * Create multiple clients.
     *
     * @param int $count
     * @param Faker|null $faker
     * @return array List of inserted client IDs
     */
    public function createMany(int $count, ?Faker $faker = null): array
    {
        $faker = $faker ?? app(Faker::class);
        $ids = [];

        for ($i = 0; $i < $count; $i++) {
            $client = $this->definition($faker);
            $ids[] = $this->create($client);
        }

        return $ids;
    }
}
