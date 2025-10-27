<?php

namespace App\Channels\Sms\Drivers;

use App\Channels\Sms\SmsProvider;
use Illuminate\Support\Facades\Http;

class Driver
{

    public function __construct(protected readonly SmsProvider $smsProvider)
    {

    }

    public function getHttpClient(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withoutVerifying()
            ->maxRedirects(3)
            ->timeout(30);

    }
}
