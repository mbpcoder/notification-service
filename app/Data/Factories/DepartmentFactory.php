<?php

namespace App\Data\Factories;

use App\Data\Entities\Department;
use stdClass;

class DepartmentFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Department
    {
        $department = new Department();

        $department->id = $entity->id;
        $department->name = $entity->name;
        $department->createdAt = $entity->created_at;
        $department->updatedAt = $entity->updated_at;

        return $department;
    }
}
