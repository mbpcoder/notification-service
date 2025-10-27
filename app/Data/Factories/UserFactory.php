<?php

namespace App\Data\Factories;

use App\Data\Entities\User;
use stdClass;

class UserFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): User
    {
        $user = new User();

        $user->id = $entity->id;
        $user->name = $entity->name;
        $user->email = $entity->email;
        $user->emailVerifiedAt = $entity->email_verified_at;
        $user->password = $entity->password;
        $user->rememberToken = $entity->remember_token;
        $user->createdAt = $entity->created_at;
        $user->updatedAt = $entity->updated_at;

        return $user;
    }
}
