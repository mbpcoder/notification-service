<?php

namespace App\Data\Entities;

use App\Data\Enums\SmsProviderStatusEnum;

class SmsAttempt extends Entity
{
    public int $id;

    public int $smsId;

    public SmsProviderStatusEnum $providerStatus;

    public string $response;

    public int $responseCode;

    public null|string $createdAt = null;
}
