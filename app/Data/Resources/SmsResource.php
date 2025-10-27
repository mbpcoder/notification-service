<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\Sms;

class SmsResource extends Resource
{
    public function __construct(
        private readonly SmsAttemptResource $smsAttemptResource
    )
    {
    }

    public function toArray(Entity|Sms $entity): array
    {
        return [
            'id' => $entity->id,
            'department_id' => $entity->departmentId,
            'client_id' => $entity->clientId,
            'provider_id' => $entity->providerId,
            'line_id' => $entity->lineId,
            'mobile' => $entity->mobile,
            'template_name' => $entity->templateName,
            'template_parameter1' => $entity->templateParameter1,
            'template_parameter2' => $entity->templateParameter2,
            'template_parameter3' => $entity->templateParameter3,
            'template_parameter4' => $entity->templateParameter4,
            'message' => $entity->message,
            'retry_count' => $entity->retryCount,
            'status' => $entity->status->value,
            'due_at' => $entity->dueAt,
            'sent_at' => $entity->sentAt,
            'delivered_at' => $entity->deliveredAt,
            'expired_at' => $entity->expiredAt,
            'created_at' => $entity->createdAt,
            'updated_at' => $entity->updatedAt,
        ];
    }

    public function toArrayWithForeignKeys($sms): array
    {
        return $this->toArray($sms) + [
                'attempts' => $sms->attempts
                    ? $this->smsAttemptResource->collectionToArray($sms->attempts)
                    : [],
            ];
    }
}
