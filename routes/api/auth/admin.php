<?php

use App\Http\Controllers\Api\Admins\Auth\AdminAuthController;
use App\Http\Controllers\Api\Admins\Auth\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Middleware\AuthRateLimiter;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthRateLimiter::class])->group(function () {

    // Define routes for login, register, logout as Admin
    Route::domain('dashboard.' . env('APP_URL'))->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('register', [AdminAuthController::class, 'register'])->middleware('auth:admin'); // Admins can be created by another admin only
        Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin');
        // Send Reset password Email
        Route::post('password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail']);
    });
});
