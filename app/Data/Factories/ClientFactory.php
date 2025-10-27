<?php

namespace App\Data\Factories;

use App\Data\Entities\Client;
use stdClass;

class ClientFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Client
    {
        $client = new Client();

        $client->id = $entity->id;
        $client->departmentId = $entity->department_id;
        $client->name = $entity->name;
        $client->token = $entity->token;
        $client->isActive = $entity->is_active;
        $client->expiredAt = $entity->expired_at;
        $client->createdAt = $entity->created_at;
        $client->updatedAt = $entity->updated_at;

        return $client;
    }
}
