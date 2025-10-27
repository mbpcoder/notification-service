<?php

namespace App\Data\Resources;

use App\Data\Entities\Department;
use App\Data\Entities\Entity;

class DepartmentResource extends Resource
{
    public function toArray(Entity|Department $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt,

        ];
    }
}
