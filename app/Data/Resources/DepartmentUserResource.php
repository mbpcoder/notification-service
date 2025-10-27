<?php

namespace App\Data\Resources;

class DepartmentUserResource extends Resource
{
    public function toArray($departmentUser): array
    {
        return [
            'id' => $departmentUser->getId(),
            'department_id' => $departmentUser->getDepartmentId(),
            'user_id' => $departmentUser->getUserId(),
            'created_at' => $departmentUser->getCreatedAt(),
            'updated_at' => $departmentUser->getUpdatedAt(),

        ];
    }

    public function toArrayWithForeignKeys($departmentUser): array
    {
        return $this->toArray($departmentUser) + [

            ];
    }
}
