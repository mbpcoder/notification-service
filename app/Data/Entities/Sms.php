<?php

namespace App\Data\Entities;

use App\Data\Enums\SmsStatusEnum;
use Illuminate\Support\Collection;

class Sms extends Entity
{
    public int $id;
    public int $departmentId;

    public int $clientId;

    public int $providerId;
    public int $lineId;

    public string $mobile;

    public null|string $templateName = null;

    public null|string $templateParameter1 = null;

    public null|string $templateParameter2 = null;

    public null|string $templateParameter3 = null;

    public null|string $templateParameter4 = null;

    public string $message;

    public int $retryCount = 0;

    public SmsStatusEnum $status;

    public null|string $dueAt = null;

    public null|string $sentAt = null;

    public null|string $deliveredAt = null;

    public null|string $expiredAt = null;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;

    /**
     * @var Collection<SmsAttempt>|null
     */
    public Collection|null $attempts = null;
}
