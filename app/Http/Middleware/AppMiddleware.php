<?php

namespace App\Http\Middleware;

use App\Contracts\ApplicationClientInterface;
use App\Data\Enums\HttpStatusEnum;
use App\Http\Responses\Response;
use Closure;
use Illuminate\Http\Request;

class AppMiddleware
{
    protected ApplicationClientInterface $client;

    public function __construct(ApplicationClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = new Response();

        if (!$this->client->check()) {
            $response->code = HttpStatusEnum::FORBIDDEN;
            $response->message = __('Forbidden or expired client token');
            return $response->toJson();
        }

        return $next($request);
    }
}
