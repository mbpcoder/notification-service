<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use App\Data\Entities\Message;

class MessageResource extends Resource
{
    public function toArray(Entity|Message $entity): array
    {
        return [
            'id' => $entity->id,
            'content' => $entity->content,
            'content_md5' => $entity->contentMd5,
            'created_at' => $entity->createdAt,
        ];
    }
}
