<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Notification Api';
});

Route::prefix('/webhook/sms/{provider_slug}')->group(function () {
    Route::get('/', [WebhookController::class, 'updateStatus']);
});
