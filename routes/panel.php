<?php

use App\Http\Controllers\Panel\ArtisanController;
use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\ClientsController;
use App\Http\Controllers\Panel\CredentialsController;
use App\Http\Controllers\Panel\DepartmentsController;
use App\Http\Controllers\Panel\LinesController;
use App\Http\Controllers\Panel\ProvidersController;
use App\Http\Controllers\Panel\SmsController;
use App\Http\Controllers\Panel\UsersController;
use App\Http\Middleware\AppMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(AppMiddleware::class)->group(function () {

    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    //   Route::middleware('auth.token')->group(function () {


    Route::prefix('/users')
        ->controller(UsersController::class)
        ->name('users.')
        ->group(function () {
            Route::post('/list', 'list')->name('list');
        });

    Route::prefix('/providers')
        ->controller(ProvidersController::class)
        ->name('providers.')
        ->group(function () {
            Route::post('/list', 'list')->name('list');
            Route::get('/{id}/show', 'show')->name('update');
            Route::post('/store', 'store')->name('store');
            Route::post('/{id}/update', 'update')->name('update');
        });

    Route::prefix('/credentials')
        ->controller(CredentialsController::class)
        ->name('credentials.')
        ->group(function () {
            Route::post('/list', 'list')->name('list');
            Route::post('/store', 'store')->name('store');
            Route::post('/{id}/update', 'update')->name('update');
        });


    Route::prefix('/sms')
        ->controller(SmsController::class)
        ->name('sms.')
        ->group(function () {
            Route::post('/list', 'list')->name('list');
        });

    Route::prefix('/lines')
        ->controller(LinesController::class)
        ->name('lines.')
        ->group(function () {
            Route::post('/list', 'list')->name('list');
            Route::get('/{id}/show', 'show')->name('update');
            Route::post('/store', 'store')->name('store');
            Route::post('/{id}/update', 'update')->name('update');
        });

    Route::prefix('/clients')
        ->controller(ClientsController::class)
        ->name('clients.')->group(function () {
            Route::post('/list', 'list')->name('list');
            Route::post('/store', 'store')->name('store');
            Route::post('/{id}/update', 'update')->name('update');
            Route::post('/{id}/revoke-token', 'revokeToken')->name('revoke-token');
        });

    Route::prefix('/departments')
        ->controller(DepartmentsController::class)
        ->name('departments.')
        ->group(function () {
            Route::post('/list', 'list')->name('list');
            Route::post('/store', 'store')->name('store');
            Route::post('/{id}/update', 'update')->name('update');
        });
    // });
});


