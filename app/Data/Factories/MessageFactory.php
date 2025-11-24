<?php

namespace App\Data\Factories;

use App\Data\Entities\Message;
use stdClass;

class MessageFactory extends Factory
{
    public function makeEntityFromStdClass(stdClass $entity): Message
    {
        $message = new Message();

        $message->id = $entity->id;
        $message->content = $entity->content;
        $message->contentMd5 = $entity->content_md5;
        $message->createdAt = $entity->created_at;

        return $message;
    }
}
