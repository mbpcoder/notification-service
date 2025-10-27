<?php

namespace App\Data\Entities;

class DepartmentUser extends Entity
{
    public int $id;

    public int $departmentId;

    public int $userId;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;
}
