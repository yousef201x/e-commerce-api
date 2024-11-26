<?php

use App\Http\Controllers\Api\Admins\Auth\AdminAuthController;
use App\Http\Controllers\Api\Users\Auth\UserAuthController;
use App\Http\Controllers\Api\Admins\Auth\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Api\Users\Auth\ForgotPasswordController;
use App\Http\Middleware\AuthRateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::middleware(AuthRateLimiter::class)->group(function () {

    // Define routes for login, register, logout as user
    Route::domain(env('APP_URL'))->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('register', [UserAuthController::class, 'register']);
        Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
        // Send Reset password Email
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    });

    // Define routes for login, register, logout as Admin
    Route::domain('dashboard.' . env('APP_URL'))->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('register', [AdminAuthController::class, 'register']);
        Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
        // Send Reset password Email
        Route::post('password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail']);
    });

    Route::get('/test-email', function () {
        try {
            Mail::raw('This is a test email', function ($message) {
                $message->to('your-email@example.com')->subject('Test Email');
            });
            return 'Email sent successfully!';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    });
});
