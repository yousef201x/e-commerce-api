<?php

use App\Http\Controllers\Api\Admins\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ActionRateLimiter;
use App\Http\Middleware\ReaderRateLimiter;

Route::domain('dashboard.' . env('APP_URL'))->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::middleware(ReaderRateLimiter::class)->group(function () {
            Route::get('/admin', [AccountController::class, 'adminInfo']);
        });

        Route::middleware(ActionRateLimiter::class)->group(function () {
            Route::post('/admin/update/name', [AccountController::class, 'updateName']);
        });

        Route::middleware(ActionRateLimiter::class)->group(function () {
            Route::post('/admin/update/email', [AccountController::class, 'updateEmail']);
        });
    });
});
