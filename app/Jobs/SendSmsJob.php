<?php

namespace App\Jobs;

use App\Channels\Sms\SmsManager;
use App\Channels\Sms\SmsMessage;
use App\Channels\Sms\SmsProvider;
use App\Data\Entities\Credential;
use App\Data\Entities\Line;
use App\Data\Entities\Provider;
use App\Data\Entities\Sms;
use App\Data\Entities\SmsAttempt;
use App\Data\Enums\SmsStatusEnum;
use App\Data\Repositories\Sms\SmsRepository;
use App\Data\Repositories\SmsAttempt\SmsAttemptRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(
        private Sms                      $sms,
        private readonly Provider|null   $provider,
        private readonly Line|null       $line,
        private readonly Credential|null $credential
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /**
         * SmsRepository
         */
        $smsRepository = app(SmsRepository::class);
        /**
         * SmsAttemptRepository
         */
        $smsAttemptRepository = app(SmsAttemptRepository::class);

        $smsProvider = new SmsProvider();
        $smsProvider->className = $this->provider->className;
        $smsProvider->baseUrl = $this->provider->url;

        $smsProvider->username = $this->credential->username;
        $smsProvider->password = $this->credential->password;
        $smsProvider->token = $this->credential->token;

        $smsMessage = new SmsMessage();
        $smsMessage->from = $this->line->number ?? null;
        $smsMessage->to = $this->sms->mobile;
        $smsMessage->content = $this->sms->message;

        $smsChannel = new SmsManager($smsProvider);
        $smsResponse = $smsChannel->send($smsMessage);

        $this->sms->retryCount += 1;

        $smsAttempt = new SmsAttempt();
        $smsAttempt->smsId = $this->sms->id;
        $smsAttempt->providerStatus = $smsResponse->providerStatus;
        $smsAttempt->responseCode = $smsResponse->httpStatusCode;
        $smsAttempt->response = json_encode($smsResponse->originalResponse);
        $smsAttemptRepository->create($smsAttempt);

        if ($smsResponse->isSuccess()) {
            $this->sms->sentAt = date('Y-m-d H:i:s');
            $this->sms->status = SmsStatusEnum::SUCCESS;
        } else {
            $this->sms->status = SmsStatusEnum::ERROR;
        }

        $smsRepository->update($this->sms);
    }
}
