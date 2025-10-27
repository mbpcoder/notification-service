<?php

namespace App\Http\Controllers\Panel;

use App\Data\Entities\Client;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\Client\ClientRepository;
use App\Data\Resources\ClientResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ClientStoreRequestPanel;
use App\Http\Requests\Panel\ListRequestPanel;
use Illuminate\Http\JsonResponse;

class ClientsController extends Controller
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly ClientResource   $clientResource
    )
    {
        parent::__construct();
    }

    public function list(ListRequestPanel $request): JsonResponse
    {
        [$total, $providers] = $this->clientRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('clients', $this->clientResource->collectionToArray($providers));
        return $this->response->toJson();
    }

    public function store(ClientStoreRequestPanel $request): JsonResponse
    {
        $client = new Client();
        $client->departmentId = $request->departmentId;
        $client->name = $request->name;
        $client->token = $this->generateToken();
        $client->isActive = $request->isActive;
        $client->expiredAt = $request->expiredAt;

        $client = $this->clientRepository->create($client);

        $this->response->value->add('client', new ClientResource()->toArray($client));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();

    }

    public function update(ClientStoreRequestPanel $request, $id): JsonResponse
    {
        $client = $this->clientRepository->getOneById($id);

        if ($client == null) {
            $this->response->code = HttpStatusEnum::NOT_FOUND;
            $this->response->message = __('Not Found');
            return $this->response->toJson();
        }

        $client->name = $request->name;
        $client->isActive = $request->isActive;
        $client->expiredAt = $request->expiredAt;

        $this->clientRepository->update($client);

        $this->response->value->add('client', (new ClientResource())->toArray($client));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }

    public function revokeToken($id): JsonResponse
    {
        $client = $this->clientRepository->getOneById($id);

        if ($client == null) {
            $this->response->code = HttpStatusEnum::NOT_FOUND;
            $this->response->message = __('Not Found');
            return $this->response->toJson();
        }

        $client->token = $this->generateToken();

        $this->clientRepository->update($client);

        $this->response->value->add('client', (new ClientResource())->toArray($client));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }

    private function generateToken($length = 64): string
    {
        $possibleChars = '123456789abcdefghikmnopqrstyxwz';
        $possibleCharsIndex = strlen($possibleChars) - 1;

        $rndString = '';
        for ($i = 0; $i < $length; $i++) {
            $rndString .= $possibleChars[rand(0, $possibleCharsIndex)];
        }
        return $rndString;
    }
}
