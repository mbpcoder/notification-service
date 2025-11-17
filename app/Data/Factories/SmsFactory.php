<?php

namespace App\Data\Factories;

use App\Data\DataTransferObject\SendBulkSmsDto;
use App\Data\Entities\Sms;
use App\Data\Enums\SmsStatusEnum;
use Illuminate\Support\Collection;
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

    public function makeCollectionFromBulkDto(SendBulkSmsDto $sendBulkSmsDto, int $providerId, int $lineId): Collection
    {
        $bulkSms = collect();
        $apiClient = apiClient();

        foreach ($sendBulkSmsDto->mobileList as $_mobile) {

            $sms = new Sms();

            $sms->departmentId = $apiClient->departmentId;
            $sms->clientId = $apiClient->id;
            $sms->providerId = $providerId;
            $sms->mobile = $_mobile;
            $sms->message = $sendBulkSmsDto->message;
            $sms->lineId = $lineId;
            $sms->status = SmsStatusEnum::PENDING;
            $sms->dueAt = $sendBulkSmsDto->dueAt?->format('Y-m-d H:i:s');
            $sms->expiredAt = $sendBulkSmsDto->expiredAt->format('Y-m-d H:i:s');

            $bulkSms->push($sms);
        }

        return $bulkSms;
    }
}
