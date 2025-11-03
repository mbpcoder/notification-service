<?php

namespace App\Channels\Sms;

use App\Channels\Sms\Drivers\ISmsDriver;

class SmsManager
{
    protected ISmsDriver $driver;

    public function __construct(SmsProvider $smsProvider)
    {
        $this->driver = $this->getDriver($smsProvider);
    }

    public function send(SmsMessage $smsMessage): SmsResponse
    {
        return $this->driver->send($smsMessage);
    }

    public function parseStatusUpdateWebhook(array $data)
    {
        return $this->driver->parseStatusUpdateWebhook($data);
    }

    private function getDriver(SmsProvider $smsProvider): ISmsDriver
    {
        $className = $smsProvider->className ?? ucfirst((string)config('channels.notification.sms.driver'));
        $driverName = 'App\\Channels\\Sms\\Drivers\\' . $className;
        return new $driverName($smsProvider);
    }
}
