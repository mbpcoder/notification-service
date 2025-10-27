<?php

namespace App\Data\Resources;

use App\Data\Entities\Credential;
use App\Data\Entities\Entity;

class CredentialResource extends Resource
{
    public function toArray(Entity|Credential $entity): array
    {
        return [
            'id' => $entity->id,
            'entity' => $entity->entity->value,
            'entity_id' => $entity->entityId,
            'username' => $entity->username,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt
        ];
    }
}
