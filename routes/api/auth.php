<?php

use App\Http\Controllers\Api\Admins\AdminAuthController;
use App\Http\Controllers\Api\Users\UserAuthController;
use App\Http\Middleware\AuthRateLimiter;
use App\Http\Middleware\VerifyAdminAuth;
use App\Http\Middleware\VerifyUserAuth;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthRateLimiter::class)->group(function () {
    // Define routes for login, register, logout as user
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

    // Define routes for login, register, logout as Admin

    Route::domain('dashboard.', env('APP_URL'))->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('register', [AdminAuthController::class, 'register']);
        Route::post('logout', [AdminAuthController::class, 'logout'])->middleware(VerifyAdminAuth::class);
    });
});
