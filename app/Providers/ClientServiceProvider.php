<?php

namespace App\Providers;

use App\Client;
use App\Contracts\ApplicationClientInterface;
use App\Data\Repositories\Client\ClientRepository;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('client', function ($app) {
            return new Client($app);
        });

        $this->app->singleton(ApplicationClientInterface::class, function ($app) {
            return $app['client'];
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['client']->viaRequest(function ($request) {
            $clientToken = $request->header('Client-Token') ?? $request->get('Client-Token');

            if (!empty($clientToken)) {
                $clientRepository = new ClientRepository();
                return $clientRepository->getOneActiveByToken($clientToken);
            }
            return null;
        });
    }
}
