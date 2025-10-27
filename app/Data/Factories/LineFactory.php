<?php

namespace App\Data\Factories;

use App\Data\Entities\Line;
use stdClass;

class LineFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Line
    {
        $line = new Line();

        $line->id = $entity->id;
        $line->providerId = $entity->provider_id;
        $line->number = $entity->number;
        $line->description = $entity->description;
        $line->isActive = $entity->is_active === 1;
        $line->isDefault = $entity->is_default === 1;
        $line->isReceivable = $entity->is_receivable === 1;
        $line->createdAt = $entity->created_at;
        $line->updatedAt = $entity->updated_at;

        return $line;
    }
}
