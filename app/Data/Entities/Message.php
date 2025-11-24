<?php

namespace App\Data\Entities;

class Message extends Entity
{
    public int $id;

    public string $content;
    public string $contentMd5;

    public null|string $createdAt = null;
}
