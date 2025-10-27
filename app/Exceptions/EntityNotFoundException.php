<?php

namespace App\Exceptions;

use App\Contracts\ApplicationExceptionInterface;
use App\Data\Enums\HttpStatusEnum;
use App\Http\Responses\Response;
use Exception;
use Illuminate\Http\JsonResponse;

class EntityNotFoundException extends Exception implements ApplicationExceptionInterface
{
    protected Response $response;

    public function __construct(string|null $message = null)
    {
        $message = $message ?? __('Not Found');
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        $response = new Response(HttpStatusEnum::NOT_FOUND);
        $response->message = $this->message;
        return $response->toJson();
    }
}
