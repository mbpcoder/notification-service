<?php

namespace App\Data\Factories;

use App\Data\Entities\Provider;
use stdClass;

class ProviderFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Provider
    {
        $provider = new Provider();

        $provider->id = $entity->id;
        $provider->name = $entity->name;
        $provider->className = $entity->class_name;
        $provider->slug = $entity->slug;
        $provider->url = $entity->url;
        $provider->isActive = $entity->is_active === 1;
        $provider->isDefault = $entity->is_default === 1;
        $provider->createdAt = $entity->created_at;
        $provider->updatedAt = $entity->updated_at;

        return $provider;
    }
}
