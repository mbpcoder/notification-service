<?php

namespace App\Data\Repositories\Message;

use App\Data\Entities\Message;
use Illuminate\Support\Collection;

readonly class MessageRepository implements IMessageRepository
{
    public function __construct(
        private MySqlMessageRepository $repository
    )
    {

    }


    public function getAll(int $offset, int $count): array
    {
        return $this->repository->getAll($offset, $count);
    }

    public function getAllByIds(array $ids): Collection
    {
        return $this->repository->getAllByIds($ids);
    }


    public function getOneById(int $id): null|Message
    {
        return $this->repository->getOneById($id);
    }

    public function getOneByContentMd5(string $contentMd5): null|Message
    {
        return $this->repository->getOneByContentMd5($contentMd5);
    }

    public function create(Message $message): Message
    {
        return $this->repository->create($message);
    }

}
