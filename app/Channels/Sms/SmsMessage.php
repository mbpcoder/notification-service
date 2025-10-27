<?php

namespace App\Channels\Sms;

class SmsMessage
{
    public int|string $from;

    public int|string $to;
    public string $content;
    public string|null $uniqueKey;
    public string|null $template;
    public array|null $params;
}
