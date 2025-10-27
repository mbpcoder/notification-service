<?php

namespace App\Data\Entities;

class Department extends Entity
{
    public int $id;

    public string $name;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;
}
