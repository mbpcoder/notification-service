<?php

namespace App\Data\Factories;

use App\Data\Entities\SmsAttempt;
use App\Data\Enums\SmsProviderStatusEnum;
use stdClass;

class SmsAttemptFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): SmsAttempt
    {
        $smsAttempt = new SmsAttempt();

        $smsAttempt->id = $entity->id;
        $smsAttempt->smsId = $entity->sms_id;
        $smsAttempt->providerStatus = $entity->provider_status !== null ? SmsProviderStatusEnum::from($entity->provider_status) : null;
        $smsAttempt->response = $entity->response;
        $smsAttempt->responseCode = $entity->response_code;
        $smsAttempt->createdAt = $entity->created_at;

        return $smsAttempt;
    }
}
