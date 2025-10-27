<?php

namespace App\Providers;

use App\Data\Repositories\User\UserRepository;
use App\Helpers\JDate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Auth::provider('repository', function ($app, array $config) {
            return new RepositoryUserProvider($app->make(UserRepository::class));
        });

        $this->app->singleton(\App\Helpers\JDate::class, function () {
            return new JDate();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
