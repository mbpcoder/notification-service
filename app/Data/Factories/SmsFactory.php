<?php

namespace App\Data\Factories;

use App\Data\Entities\Sms;
use App\Data\Enums\SmsStatusEnum;
use stdClass;

class SmsFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Sms
    {
        $sms = new Sms();

        $sms->id = $entity->id;
        $sms->departmentId = $entity->department_id;
        $sms->clientId = $entity->client_id;
        $sms->providerId = $entity->provider_id;
        $sms->lineId = $entity->line_id;
        $sms->mobile = $entity->mobile;
        $sms->templateName = $entity->template_name;
        $sms->templateParameter1 = $entity->template_parameter1;
        $sms->templateParameter2 = $entity->template_parameter2;
        $sms->templateParameter3 = $entity->template_parameter3;
        $sms->templateParameter4 = $entity->template_parameter4;
        $sms->message = $entity->message;
        $sms->retryCount = $entity->retry_count;
        $sms->status = SmsStatusEnum::from($entity->status);
        $sms->dueAt = $entity->due_at;
        $sms->sentAt = $entity->sent_at;
        $sms->deliveredAt = $entity->delivered_at;
        $sms->expiredAt = $entity->expired_at;
        $sms->createdAt = $entity->created_at;
        $sms->updatedAt = $entity->updated_at;

        return $sms;
    }
}
