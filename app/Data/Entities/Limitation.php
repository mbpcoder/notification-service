<?php

namespace App\Data\Entities;

class Limitation extends Entity
{
    public int $id;

    public string $departmentId;
    public string|null $userId = null;
    public int|null $monthlyAllowedSmsCount = null;
    public int|null $allowedSmsPartCount = null;
    public string|null $startAt = null;
    public string|null $endAt = null;

    public bool $isActive = false;

    public null|string $createdAt = null;

    public null|string $updatedAt = null;
}
