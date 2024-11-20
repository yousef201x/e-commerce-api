<?php

use App\Http\Controllers\Api\Admins\AdminAuthController;
use App\Http\Controllers\Api\Users\UserAuthController;
use Illuminate\Support\Facades\Route;

// Define routes for login, register, logout as user
Route::post('login', [UserAuthController::class, 'login']);
Route::post('register', [UserAuthController::class, 'register']);
Route::post('logout', [UserAuthController::class, 'logout']);

// Define routes for login, register, logout as Admin
Route::prefix("/admin")->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
});
