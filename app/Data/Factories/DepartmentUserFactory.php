<?php

namespace App\Data\Factories;

use App\Data\Entities\DepartmentUser;
use stdClass;

class DepartmentUserFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): DepartmentUser
    {
        $departmentUser = new DepartmentUser();

        $departmentUser->id = $entity->id;
        $departmentUser->departmentId = $entity->department_id;
        $departmentUser->userId = $entity->user_id;
        $departmentUser->createdAt = $entity->created_at;
        $departmentUser->updatedAt = $entity->updated_at;

        return $departmentUser;
    }
}
