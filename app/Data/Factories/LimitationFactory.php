<?php

namespace App\Data\Factories;

use App\Data\Entities\Limitation;
use stdClass;

class LimitationFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Limitation
    {
        $limitation = new Limitation();

        $limitation->id = $entity->id;
        $limitation->departmentId = $entity->department_id;
        $limitation->userId = $entity->user_id;
        $limitation->monthlyAllowedSmsCount = $entity->monthly_allowed_sms_count;
        $limitation->allowedSmsPartCount = $entity->allowed_sms_part_count;
        $limitation->startAt = $entity->start_at;
        $limitation->endAt = $entity->end_at;
        $limitation->isActive = $entity->is_active;
        $limitation->createdAt = $entity->created_at;
        $limitation->updatedAt = $entity->updated_at;

        return $limitation;
    }
}
