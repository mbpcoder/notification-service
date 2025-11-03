<?php

use App\Http\Controllers\Api\SmsController;
use App\Http\Middleware\AppMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(AppMiddleware::class)->group(function () {
    Route::prefix('/sms')->group(function () {
        Route::post('/send', [SmsController::class, 'send']);
        Route::post('/send-otp', [SmsController::class, 'sendOtp']);
    });
});
