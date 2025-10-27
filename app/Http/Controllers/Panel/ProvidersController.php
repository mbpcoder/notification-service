<?php

namespace App\Http\Controllers\Panel;

use App\Data\Entities\Credential;
use App\Data\Entities\Provider;
use App\Data\Enums\CredentialEntityEnum;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\Credential\CredentialRepository;
use App\Data\Repositories\Provider\ProviderRepository;
use App\Data\Resources\ProviderResource;
use App\Exceptions\EntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ProviderListRequestPanel;
use App\Http\Requests\Panel\ProviderStoreRequestPanel;
use Illuminate\Http\JsonResponse;

class ProvidersController extends Controller
{
    public function __construct(
        private readonly ProviderRepository $providerRepository,
        private readonly CredentialRepository $credentialRepository,
        private readonly ProviderResource     $providerResource,

    )
    {
        parent::__construct();
    }

    public function list(ProviderListRequestPanel $request): JsonResponse
    {
        [$total, $providers] = $this->providerRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('providers', $this->providerResource->collectionToArray($providers));
        return $this->response->toJson();
    }

    public function show($id): JsonResponse
    {
        $provider = $this->providerRepository->getOneById($id);

        if ($provider == null) {
            throw new EntityNotFoundException();
        }

        $this->response->value->add('provider', $this->providerResource->toArray($provider));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }

    public function store(ProviderStoreRequestPanel $request): JsonResponse
    {
        $provider = new Provider();
        $provider->name = $request->name;
        $provider->className = $request->className;
        $provider->slug = $request->slug;
        $provider->url = $request->url;
        $provider->isActive = $request->isActive;
        $provider->isDefault = $request->isDefault;

        $provider = $this->providerRepository->create($provider);

        $credential = new Credential();
        $credential->entity = CredentialEntityEnum::PROVIDER;
        $credential->entityId = $provider->id;
        $credential->username = $request->username;
        $credential->password = $request->password;
        $credential->token = $request->token;
        $credential->isActive = $request->isActive;
        $credential = $this->credentialRepository->create($credential);

        $this->response->value->add('provider', $this->providerResource->toArray($provider));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();

    }

    public function update(ProviderStoreRequestPanel $request, $id): JsonResponse
    {
        $provider = $this->providerRepository->getOneById($id);

        if ($provider == null) {
            throw new EntityNotFoundException();
        }

        $provider->name = $request->name;
        $provider->className = $request->className;
        $provider->url = $request->url;
        $provider->isActive = $request->isActive;
        $provider->isDefault = $request->isDefault;

        $this->providerRepository->update($provider);

        $this->response->value->add('provider', $this->providerResource->toArray($provider));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }
}
