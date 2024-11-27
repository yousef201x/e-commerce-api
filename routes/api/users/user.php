<?php

use App\Http\Controllers\Api\Users\AccountController;
use App\Http\Middleware\ActionRateLimiter;
use App\Http\Middleware\ReaderRateLimiter;
use Illuminate\Support\Facades\Route;

Route::domain(env('APP_URL'))->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::middleware(ReaderRateLimiter::class)->group(function () {
            Route::get('/user', [AccountController::class, 'userInfo']);
        });

        Route::middleware(ActionRateLimiter::class)->group(function () {
            Route::post('/user/update/name', [AccountController::class, 'updateName']);
        });

        Route::middleware(ActionRateLimiter::class)->group(function () {
            Route::post('/user/update/email', [AccountController::class, 'updateEmail']);
        });
    });
});
