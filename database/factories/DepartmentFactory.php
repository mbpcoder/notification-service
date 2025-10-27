<?php

namespace Database\Factories;

use App\Data\Entities\Department;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class DepartmentFactory
{
    /**
     * Create a new department instance with fake data.
     *
     * @param Faker $faker
     * @return Department
     */
    public function definition(Faker $faker): Department
    {
        $department = new Department();
        $department->name = $faker->words(2, true); // Generates a two-word department name
        $department->createdAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $department->updatedAt = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');

        return $department;
    }

    /**
     * Insert a department into the database using query builder.
     *
     * @param Department $department
     * @return int The inserted department's ID
     */
    public function create(Department $department): int
    {
        return DB::table('departments')->insertGetId([
            'name' => $department->name,
            'created_at' => $department->createdAt,
            'updated_at' => $department->updatedAt,
        ]);
    }

    /**
     * Create multiple departments.
     *
     * @param int $count
     * @param Faker|null $faker
     * @return array List of inserted department IDs
     */
    public function createMany(int $count, ?Faker $faker = null): array
    {
        $faker = $faker ?? app(Faker::class);
        $ids = [];

        for ($i = 0; $i < $count; $i++) {
            $department = $this->definition($faker);
            $ids[] = $this->create($department);
        }

        return $ids;
    }
}
