<?php

namespace App\Jobs;

use App\Data\Enums\CredentialEntityEnum;
use App\Data\Repositories\Credential\CredentialRepository;
use App\Data\Repositories\Line\LineRepository;
use App\Data\Repositories\Provider\ProviderRepository;
use App\Data\Repositories\Sms\SmsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetReadyToSendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(
        SmsRepository        $smsRepository,
        ProviderRepository   $providerRepository,
        LineRepository       $lineRepository,
        CredentialRepository $credentialRepository,
    ): void
    {
        $allSms = $smsRepository->getAllReadyToSendSms();

        if ($allSms->count() === 0) {
            return;
        }

        $providerIds = $allSms->pluck('providerId')->filter()->unique()->toArray();
        $providers = $providerRepository->getAllByIds($providerIds)->keyBy('id');

        $lineIds = $allSms->pluck('lineId')->filter()->unique()->toArray();
        $lines = $lineRepository->getAllByIds($lineIds)->keyBy('id');

        $credentials = $credentialRepository->getAllByProviderIdsAndLineIds($providerIds, $lineIds)->keyBy('id');

        $smsRepository->updateSendingSms($allSms);

        foreach ($allSms as $sms) {
            $credential = $credentials->where('entity', CredentialEntityEnum::LINE)->firstWhere('entityId', $sms->lineId);
            if ($credential === null) {
                $credential = $credentials->where('entity', CredentialEntityEnum::PROVIDER)->firstWhere('entityId', $sms->providerId);
            }
            dispatch(new SendSmsJob($sms, $providers[$sms->providerId] ?? null, $lines[$sms->lineId] ?? null, $credential ?? null));
        }
    }
}

