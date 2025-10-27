<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\User;

class UserResource extends Resource
{
    public function toArray(Entity|User $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'email' => $entity->email,
            'remember_token' => $entity->rememberToken
        ];
    }
}
