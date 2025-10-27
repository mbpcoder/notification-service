<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\Sms;
use App\Data\Entities\SmsAttempt;

class SmsAttemptResource extends Resource
{

    public function toArray(Entity|SmsAttempt $entity): array
    {
        return [
            'id' => $entity->id,
            'sms_id' => $entity->smsId,
            'provider_status' => $entity->providerStatus,
            'response' => $entity->response,
            'response_code' => $entity->responseCode,
            'created_at' => $entity->createdAt,
        ];
    }
}
