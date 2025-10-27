<?php

namespace App\Data\Resources;

use App\Data\Entities\Client;
use App\Data\Entities\Entity;

class ClientResource extends Resource
{
    public function toArray(Entity|Client $entity): array
    {
        return [
            'id' => $entity->id,
            'department_id' => $entity->departmentId,
            'name' => $entity->name,
            'token' => $entity->token,
            'is_active' => $entity->isActive,
            'expired_at' => $entity->expiredAt,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt
        ];
    }
}
