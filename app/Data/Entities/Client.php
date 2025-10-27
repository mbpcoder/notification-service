<?php

namespace App\Data\Entities;

class Client extends Entity
{
    public int $id;

    public int $departmentId;

    public string $name;

    public string $token;

    public bool $isActive = false;

    public null|string $expiredAt = null;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;
}
