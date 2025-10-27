<?php

namespace App\Channels\Sms\Drivers;

use App\Channels\Sms\SmsMessage;
use App\Channels\Sms\SmsResponse;

interface ISmsDriver
{
    public function send(SmsMessage $smsMessage): SmsResponse;
}
