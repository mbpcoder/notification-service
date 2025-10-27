<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\Limitation;

class LimitationResource extends Resource
{
    public function toArray(Entity|Limitation $entity): array
    {
        return [
            'id' => $entity->id,
            'department_id' => $entity->departmentId,
            'user_id' => $entity->userId,
            'monthly_allowed_sms_count' => $entity->monthlyAllowedSmsCount,
            'allowed_sms_part_count' => $entity->allowedSmsPartCount,
            'start_at' => $entity->startAt,
            'end_at' => $entity->endAt,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt,
        ];
    }
}
