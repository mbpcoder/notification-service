<?php

namespace App\Data\Entities;

use App\Data\Enums\CredentialEntityEnum;

class Credential extends Entity
{
    public int $id;

    public CredentialEntityEnum $entity;

    public int $entityId;

    public null|string $username = null;

    public null|string $password = null;

    public null|string $token = null;

    public bool $isActive = false;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;

    public null|string $deletedAt = null;
}
