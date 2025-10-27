<?php

namespace App\Http\Requests\Panel;

use App\Data\Enums\CredentialEntityEnum;
use App\Http\Requests\BaseRequest;

class CredentialStoreRequestPanel extends BaseRequest
{
    public readonly CredentialEntityEnum $entity;
    public readonly int $entityId;
    public readonly string|null $username;
    public readonly string|null $password;
    public readonly string|null $token;
    public readonly bool $isActive;

    public function rules(): array
    {
        return [

            'entity_id' => 'required|integer',
            'username' => 'nullable|string|max:255|min:2',
            'password' => 'nullable|string|max:255|min:6',
            'token' => 'nullable|string|max:500|min:6',
            'is_active' => 'nullable|boolean',
        ];
    }

    protected function passedValidation(): void
    {
        $this->entity = CredentialEntityEnum::tryFrom($this->get('entity'));
        $this->entityId = $this->get('entity_id');
        $this->username = $this->get('username');
        $this->password = $this->get('password');
        $this->token = $this->get('token');
        $this->isActive = $this->get('is_active', false);
    }
}
