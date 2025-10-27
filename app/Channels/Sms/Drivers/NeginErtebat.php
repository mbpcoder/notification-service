<?php

namespace App\Channels\Sms\Drivers;

use App\Channels\Sms\SmsResponse;
use App\Data\Enums\SmsProviderStatusEnum;
use App\Data\Enums\SmsStatusEnum;

class NeginErtebat extends Driver implements ISmsDriver
{
    public function send($smsMessage): SmsResponse
    {
        $http = $this->getHttpClient();
        $http->acceptJson();

        $smsResponse = new SmsResponse();
        try {
            $response = $http->get($this->smsProvider->baseUrl . '/wsSend.ashx', [
                'username' => $this->smsProvider->username,
                'password' => $this->smsProvider->password,
                'line' => $smsMessage->from,
                'mobile' => $smsMessage->to,
                'message' => $smsMessage->content,
                'life_time' => 60,
            ]);

            $smsResponse->originalResponse = $response->object();
            $smsResponse->originalHttpStatusCode = $response->status();
            $smsResponse->providerStatus = $this->getState($smsResponse->originalResponse);

            if ($smsResponse->providerStatus === SmsProviderStatusEnum::SUCCESS) {
                $smsResponse->status = SmsStatusEnum::SUCCESS;
            } else {
                $smsResponse->status = SmsStatusEnum::ERROR;
            }
            $smsResponse->httpStatusCode = $response->status();
            $smsResponse->message = is_array($smsResponse->originalResponse) ? ($smsResponse->originalResponse['msg'] ?? null) : $smsResponse->originalResponse?->msg ?? null;

        } catch (\Throwable $exception) {
            $smsResponse->message = $exception->getMessage();
            $smsResponse->httpStatusCode = method_exists($exception, 'getStatusCode') ? $exception?->getStatusCode() : 500;
            $smsResponse->status = SmsStatusEnum::ERROR;
        }

        return $smsResponse;
    }

    private function getState($originalResponse): SmsProviderStatusEnum
    {
        $status = $originalResponse?->status ?? null;
        return match ($status) {
            -2 => SmsProviderStatusEnum::SUCCESS,
            -1 => SmsProviderStatusEnum::SUCCESS,
            1 => SmsProviderStatusEnum::MOBILE_NOT_VALID,
            108 => SmsProviderStatusEnum::LINE_ARRAY_IS_EMPTY,
            108 => SmsProviderStatusEnum::MOBILE_ARRAY_IS_EMPTY,
            2 => SmsProviderStatusEnum::LINE_NOT_VALID,
            107 => SmsProviderStatusEnum::MOBILE_ARRAY_IS_BIGGER_THAN_EXPECTED,
            3 => SmsProviderStatusEnum::ENCODING_NOT_VALID,
            4 => SmsProviderStatusEnum::MESSAGE_CLASS_NOT_VALID,
            15 => SmsProviderStatusEnum::SERVER_ERROR,
            14 => SmsProviderStatusEnum::BALANCE_IS_LOW,
            16 => SmsProviderStatusEnum::ACCOUNT_IS_DISABLED,
            17 => SmsProviderStatusEnum::ACCOUNT_IS_EXPIRED,
            18 => SmsProviderStatusEnum::ACCOUNT_CREDENTIAL_NOT_VALID,
            23 => SmsProviderStatusEnum::SERVER_NOT_RESPONDING,
            25 => SmsProviderStatusEnum::REQUESTED_SERVICE_IS_NOT_VALID,
            27 => SmsProviderStatusEnum::MOBILE_CANCELED_RECEIVING_SMS,
            409 => SmsProviderStatusEnum::REQUEST_THROTTLE_PASSED,
            401 => SmsProviderStatusEnum::CREDENTIAL_IS_NOT_VALID,
            default => SmsProviderStatusEnum::UNKNOWN,
        };
    }
}
