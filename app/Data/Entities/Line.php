<?php

namespace App\Data\Entities;

class Line extends Entity
{
    public int $id;

    public int $providerId;

    public string $number;

    public null|string $description = null;

    public bool $isActive = false;

    public bool $isDefault = false;
    public bool $isReceivable = false;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;
}
