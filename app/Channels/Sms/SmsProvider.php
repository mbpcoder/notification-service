<?php

namespace App\Channels\Sms;

class SmsProvider
{
    public string|null $username = null;

    public string|null $password = null;

    public string|null $token = null;

    public string $baseUrl;

    public string $className;
}
