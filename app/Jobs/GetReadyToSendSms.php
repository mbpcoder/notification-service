<?php

namespace App\Jobs;

use App\Data\Repositories\Provider\ProviderRepository;
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
        ProviderRepository   $providerRepository,
    ): void
    {

        [$total, $providers] = $providerRepository->getAll();

        foreach ($providers as $_provider) {
            dispatch(new SendProviderSms($_provider));
        }
    }
}

