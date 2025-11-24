<?php

namespace App\Data\Repositories\Message;

use App\Data\Entities\Message;

interface IMessageRepository
{

    public function getOneById(int $id): null|Message;

    public function create(Message $message): Message;

}
