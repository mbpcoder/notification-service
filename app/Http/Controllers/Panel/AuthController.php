<?php

namespace App\Http\Controllers\Panel;

use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\User\UserRepository;
use App\Data\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\LoginRequestPanel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct();

    }

    public function login(LoginRequestPanel $request): JsonResponse
    {
        $user = $this->userRepository->getOneByEmail($request->username);

        if ($user === null || !Hash::check($request->password, $user->password)) {
            $this->response->code = HttpStatusEnum::FORBIDDEN;
            return $this->response->toJson();
        }

        $this->response->value->add('user', (new UserResource())->toArray($user));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }
}
