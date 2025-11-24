<?php

namespace App\Http\Controllers\Panel;

use App\Data\Repositories\Message\MessageRepository;
use App\Data\Resources\MessageResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ListRequestPanel;
use Illuminate\Http\JsonResponse;

class MessagesController extends Controller
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly MessageResource   $messageResource
    )
    {
        parent::__construct();
    }

    public function list(ListRequestPanel $request): JsonResponse
    {
        [$total, $messages] = $this->messageRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('messages', $this->messageResource->collectionToArray($messages));
        return $this->response->toJson();
    }
}
