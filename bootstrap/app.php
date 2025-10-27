<?php

use App\Data\Enums\HttpStatusEnum;
use App\Http\Responses\Response;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('panel')
                ->name('panel.')
                ->group(base_path('routes/panel.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->alias([
//            'auth.token' => AuthenticateWithToken::class,
//        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        \Sentry\Laravel\Integration::handles($exceptions);

        $exceptions->renderable(function (ValidationException $exception) {
            $response = new Response();
            $response->message = __('Request is not valid');
            $response->code = HttpStatusEnum::UNPROCESSABLE_ENTITY;
            $response->error->addValidator($exception->validator);
            return $response->toJson();
        });

        $exceptions->renderable(function (Throwable $exception) {
            $response = new Response();
            $response->message = __($exception?->getMessage() ?? 'Server error');
            $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();
            $response->code = HttpStatusEnum::tryFrom($statusCode) ?? Httpstatusenum::UNKNOWN;
            return $response->toJson();
        });
    })->create();
