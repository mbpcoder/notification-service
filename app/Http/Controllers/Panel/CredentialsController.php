<?php

namespace App\Http\Controllers\Panel;

use App\Data\Entities\Credential;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\Credential\CredentialRepository;
use App\Data\Resources\CredentialResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\CredentialListRequestPanel;
use App\Http\Requests\Panel\CredentialStoreRequestPanel;
use Illuminate\Http\JsonResponse;

class CredentialsController extends Controller
{
    public function __construct(
        private readonly CredentialRepository $credentialRepository,
        private readonly CredentialResource   $credentialResource
    )
    {
        parent::__construct();

    }

    public function list(CredentialListRequestPanel $request): JsonResponse
    {
        [$total, $lines] = $this->credentialRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('credentials', $this->credentialResource->collectionToArray($lines));
        return $this->response->toJson();
    }

    public function store(CredentialStoreRequestPanel $request): JsonResponse
    {
        $credential = new Credential();

        $credential->entity = $request->entity;
        $credential->entityId = $request->entityId;
        $credential->username = $request->username;
        $credential->password = $request->password;
        $credential->token = $request->token;
        $credential->isActive = $request->isActive;
        $credential = $this->credentialRepository->create($credential);

        $this->response->value->add('credential', $this->credentialResource->toArray($credential));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();

    }

    public function update(CredentialStoreRequestPanel $request, $id): JsonResponse
    {
        $credential = $this->credentialRepository->getOneById($id);

        if ($credential == null) {
            $this->response->code = HttpStatusEnum::NOT_FOUND;
            $this->response->message = __('Not Found');
            return $this->response->toJson();
        }

        $credential->entity = $request->entity;
        $credential->entityId = $request->entityId;
        $credential->username = $request->username;
        $credential->password = $request->password;
        $credential->token = $request->token;
        $credential->isActive = $request->isActive;

        $this->credentialRepository->update($credential);

        $this->response->value->add('credential', $this->credentialResource->toArray($credential));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }
}
