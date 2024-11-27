<?php

use App\Http\Controllers\Api\Users\Socialite\SocialiteController;
use App\Http\Middleware\AuthRateLimiter;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthRateLimiter::class])->group(function () {

    Route::middleware([StartSession::class])->group(function () {
        // Define the redirect route for OAuth providers
        Route::get('auth/redirect/{provider}', [SocialiteController::class, 'redirect'])
            ->whereIn('provider', ['google']);

        // Define the callback route for OAuth providers
        Route::get('auth/callback/{provider}', [SocialiteController::class, 'callback'])
            ->whereIn('provider', ['google']);
    });
});
