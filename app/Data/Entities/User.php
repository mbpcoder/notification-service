<?php

namespace App\Data\Entities;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends Entity implements Authenticatable
{
    use CanResetPassword, Notifiable, Authorizable;

    public int $id;

    public string $name;

    public string $email;

    public null|string $emailVerifiedAt = null;

    public string $password;

    public null|string $rememberToken = null;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): int
    {
        return $this->id;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken($value): void
    {
        $this->rememberToken = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'rememberToken';
    }
}
