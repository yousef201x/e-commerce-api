<?php

use App\Http\Controllers\Api\Users\Auth\UserAuthController;
use App\Http\Controllers\Api\Users\Auth\ForgotPasswordController;
use App\Http\Middleware\AuthRateLimiter;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthRateLimiter::class])->group(function () {

    // Define routes for login, register, logout as user
    Route::domain(env('APP_URL'))->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('register', [UserAuthController::class, 'register']);
        Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
        // Send Reset password Email
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    });
});
