<?php

namespace App\Jobs;

use App\Data\Entities\Provider;
use App\Data\Enums\CredentialEntityEnum;
use App\Data\Repositories\Credential\CredentialRepository;
use App\Data\Repositories\Line\LineRepository;
use App\Data\Repositories\Sms\SmsRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendProviderSms implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Provider $provider
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        SmsRepository        $smsRepository,
        LineRepository       $lineRepository,
        CredentialRepository $credentialRepository,
    ): void
    {
        $allSms = $smsRepository->getAllReadyToSendSmsByProviderId($this->provider->id);

        if ($allSms->count() === 0) {
            return;
        }

        $lineIds = $allSms->pluck('lineId')->filter()->unique()->toArray();
        $lines = $lineRepository->getAllByIds($lineIds)->keyBy('id');

        $credentials = $credentialRepository->getAllByProviderIdsOrLineIds([$this->provider->id], $lineIds)->keyBy(function ($item) {
            return $item->entity->value . $item->entityId;
        });
        $providerCredential = $credentials->where('entity', CredentialEntityEnum::PROVIDER)->firstWhere('entityId', $this->provider->id);

        $smsRepository->updateSendingSms($allSms);

        foreach ($allSms as $sms) {
            $credential = $credentials['line' . $sms->lineId] ?? $providerCredential;
            dispatch(new SendSmsJob($sms, $this->provider, $lines[$sms->lineId] ?? null, $credential));
        }
    }
}
