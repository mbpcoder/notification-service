<?php

namespace App\Data\Entities;

class Provider extends Entity
{
    public int $id;

    public string $name;

    public string $className;

    public string $slug;

    public string $url;

    public bool $isActive = false;

    public bool $isDefault = true;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;
}
