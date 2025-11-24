<?php

namespace App\Data\Repositories\Message;

use App\Data\Entities\Message;
use App\Data\Factories\MessageFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlMessageRepository extends MySqlRepository implements IMessageRepository
{
    public function __construct()
    {
        $this->table = 'messages';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new MessageFactory();

        parent::__construct();
    }

    public function getAllByIds(array $ids): Collection
    {
        $sms = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();
        return $this->factory->makeCollectionOfEntities($sms);
    }

    public function getOneById(int $id): null|Message
    {
        $sms = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $sms ? $this->factory->makeEntityFromStdClass($sms) : null;
    }

    public function getOneByContentMd5(string $contentMd5): null|Message
    {
        $sms = $this->newQuery()
            ->where('content_md5', $contentMd5)
            ->first();

        return $sms ? $this->factory->makeEntityFromStdClass($sms) : null;
    }

    public function create(Message $message): Message
    {
        $message->createdAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'content' => $message->content,
                'content_md5' => $message->contentMd5,
                'created_at' => $message->createdAt,
            ]);

        $message->id = $id;

        return $message;
    }
}
