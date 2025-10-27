<?php

namespace App\Channels\Sms\Drivers;

use App\Channels\Sms\SmsResponse;

class Log extends Driver implements ISmsDriver
{
    public function send($smsMessage): SmsResponse
    {
        logger('sms log channel: from=' . $smsMessage->from . ', to=' . $smsMessage->to . ', content=' . $smsMessage->content);
        return new SmsResponse();
    }
}
