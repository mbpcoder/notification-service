<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\Line;

class LineResource extends Resource
{
    public function toArray(Entity|Line $entity): array
    {
        return [
            'id' => $entity->id,
            'provider_id' => $entity->providerId,
            'number' => $entity->number,
            'description' => $entity->description,
            'is_active' => $entity->isActive,
            'is_default' => $entity->isDefault,
            'is_receivable' => $entity->isReceivable,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt,
        ];
    }
}
