<?php

namespace App\Providers;

use App\Data\Entities\User;
use App\Data\Repositories\User\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;

class RepositoryUserProvider implements UserProvider
{
    public function __construct(
        protected UserRepository $userRepository
    )
    {
    }

    public function retrieveById($identifier): User|UserContract|null
    {
        return $this->userRepository->getOneById($identifier);
    }

    public function retrieveByToken($identifier, $token): UserContract|User|null
    {
        return $this->userRepository->getOneByRememberToken($token);
    }

    public function updateRememberToken(UserContract|User $user, $token): void
    {
        $user->rememberToken = $token;
        $this->userRepository->update($user);
    }

    public function retrieveByCredentials(array $credentials): null
    {
        // Not needed for token-based authentication
        return null;
    }

    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        // Not needed for token-based authentication
        return false;
    }

    public function rehashPasswordIfRequired(UserContract $user, #[\SensitiveParameter] array $credentials, bool $force = false)
    {
        // TODO: Implement rehashPasswordIfRequired() method.
    }
}
