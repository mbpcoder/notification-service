<?php

namespace App\Data\Repositories\Department;

use App\Data\Entities\Department;
use App\Data\Factories\DepartmentFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlDepartmentRepository extends MySqlRepository implements IDepartmentRepository
{


    public function __construct()
    {
        $this->table = 'departments';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new DepartmentFactory();

        parent::__construct();
    }

    public function getOneById(int $id): null|Department
    {
        $department = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $department ? $this->factory->makeEntityFromStdClass($department) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $department = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($department);
    }

    public function create(Department $department): Department
    {
        $department->createdAt = $department->updatedAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'name' => $department->name,
                'created_at' => $department->createdAt,
                'updated_at' => $department->updatedAt,
            ]);

        $department->id = $id;

        return $department;
    }

    public function update(Department $department): int
    {
        $department->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $department->id)
            ->update([
                'name' => $department->name,
                'updated_at' => $department->updatedAt,
            ]);
    }
}
