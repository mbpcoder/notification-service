<?php

namespace App\Http\Controllers\Panel;

use App\Data\Repositories\User\UserRepository;
use App\Data\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\UserListRequestPanel;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserResource   $userResource
    )
    {
        parent::__construct();
    }

    public function list(UserListRequestPanel $request): JsonResponse
    {
        [$total, $providers] = $this->userRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('users', $this->userResource->collectionToArray($providers));
        return $this->response->toJson();
    }
}
