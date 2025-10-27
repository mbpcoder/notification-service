<?php

namespace App\Channels\Sms;

use App\Data\Enums\SmsProviderStatusEnum;
use App\Data\Enums\SmsStatusEnum;

class SmsResponse
{
    public int $httpStatusCode;
    public SmsStatusEnum $status;
    public SmsProviderStatusEnum $providerStatus;

    public string|null $message;
    public int $originalHttpStatusCode;
    public mixed $originalResponse;

    public function isSuccess(): bool
    {
        return $this->httpStatusCode === 200 && $this->status === SmsStatusEnum::SUCCESS;
    }
}
