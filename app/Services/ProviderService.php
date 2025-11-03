<?php

namespace App\Services;

use App\Data\Entities\Provider;
use App\Data\Repositories\Provider\ProviderRepository;
use App\Exceptions\EntityNotFoundException;

class ProviderService
{

    public function __construct(
        private readonly ProviderRepository $providerRepository,
    )
    {

    }

    public function getProvider(string|null $providerSlug): Provider
    {
        if ($providerSlug !== null) {
            $provider = $this->providerRepository->getOneBySlug($providerSlug);

        } else {
            $provider = $this->providerRepository->getOneDefault();
        }

        if ($provider === null) {
            throw new EntityNotFoundException();
        }

        return $provider;
    }

}
