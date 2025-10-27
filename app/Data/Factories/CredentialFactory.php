<?php

namespace App\Data\Factories;

use App\Data\Entities\Credential;
use App\Data\Enums\CredentialEntityEnum;
use stdClass;

class CredentialFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Credential
    {
        $credential = new Credential();

        $credential->id = $entity->id;
        $credential->entity = CredentialEntityEnum::from($entity->entity);
        $credential->entityId = $entity->entity_id;
        $credential->username = $entity->username !== null ? decrypt($entity->username) : null;
        $credential->password = $entity->password !== null ? decrypt($entity->password) : null;
        $credential->token = $entity->token !== null ? decrypt($entity->token) : null;
        $credential->isActive = $entity->is_active;
        $credential->createdAt = $entity->created_at;
        $credential->updatedAt = $entity->updated_at;
        $credential->deletedAt = $entity->deleted_at;

        return $credential;
    }
}
