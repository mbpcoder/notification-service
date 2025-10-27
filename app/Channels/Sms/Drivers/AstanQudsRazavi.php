<?php

namespace App\Channels\Sms\Drivers;

use App\Channels\Sms\SmsResponse;
use App\Data\Enums\SmsStatusEnum;

class AstanQudsRazavi extends Driver implements ISmsDriver
{
    public function send($smsMessage): SmsResponse
    {
        $http = $this->getHttpClient();
        $http->acceptJson()->withHeaders([
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Content-Length' => '72',
            'Content-Type' => 'application/json',
            'Host' => 'iranlbs.ir',
            'Postman-Token' => $this->smsProvider->token,
            'User-Agent' => 'PostmanRuntime/7.18.0',
            'cache-control' => 'no-cache',
        ]);

        $smsResponse = new SmsResponse();

        try {
            $response = $http->post($this->smsProvider->baseUrl . '/hybrid/single-send.php', [
                'form' => $smsMessage->from,
                'to' => $smsMessage->to,
                'message' => $smsMessage->content,
            ]);
            $smsResponse->message = $response->body();
            $smsResponse->httpStatusCode = $response->status();
            $smsResponse->status = SmsStatusEnum::SUCCESS;
        } catch (\Throwable $exception) {
            $smsResponse->message = $exception->getMessage();
            $smsResponse->httpStatusCode = $exception?->getStatusCode() ?? 500;
            $smsResponse->status = SmsStatusEnum::ERROR;
        }

        return $smsResponse;
    }
}
