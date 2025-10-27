<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\Provider;

class ProviderResource extends Resource
{
    public function toArray(Entity|Provider $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'class_name' => $entity->className,
            'slug' => $entity->slug,
            'url' => $entity->url,
            'is_active' => $entity->isActive,
            'is_default' => $entity->isDefault,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt,
        ];
    }
}
