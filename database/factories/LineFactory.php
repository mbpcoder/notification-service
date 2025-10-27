<?php

namespace Database\Factories;

use App\Data\Entities\Line;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class LineFactory
{
    /**
     * Create a new line instance with fake data.
     *
     * @param Faker $faker
     * @return Line
     */
    public function definition(Faker $faker): Line
    {
        $line = new Line();
        $line->providerId = $faker->numberBetween(1, 10); // Assuming providers exist with IDs 1-10
        $line->number = $faker->numerify('###-###-####'); // Generates a phone number-like format
        $line->description = $faker->optional(0.6)->sentence; // 60% chance of having a description
        $line->isActive = $faker->boolean(70); // 70% chance of being active
        $line->isDefault = $faker->boolean(20); // 20% chance of being default
        $line->isReceivable = $faker->boolean(50); // 50% chance of being receivable
        $line->createdAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $line->updatedAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');

        return $line;
    }

    /**
     * Insert a line into the database using query builder.
     *
     * @param Line $line
     * @return int The inserted line's ID
     */
    public function create(Line $line): int
    {
        return DB::table('lines')->insertGetId([
            'provider_id' => $line->providerId,
            'number' => $line->number,
            'description' => $line->description,
            'is_active' => $line->isActive,
            'is_default' => $line->isDefault,
            'is_receivable' => $line->isReceivable,
            'created_at' => $line->createdAt,
            'updated_at' => $line->updatedAt,
        ]);
    }

    /**
     * Create multiple lines.
     *
     * @param int $count
     * @param Faker|null $faker
     * @return array List of inserted line IDs
     */
    public function createMany(int $count, ?Faker $faker = null): array
    {
        $faker = $faker ?? app(Faker::class);
        $ids = [];

        for ($i = 0; $i < $count; $i++) {
            $line = $this->definition($faker);
            $ids[] = $this->create($line);
        }

        return $ids;
    }
}
