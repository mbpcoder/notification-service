<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

//        $this->app['auth']->viaRequest('token', function (Request $request) {
//            $token = $request->header('Auth-Token');
//
//            return $user;
//        });

    }
}
