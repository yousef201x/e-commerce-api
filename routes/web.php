<?php

use App\Http\Controllers\Api\Users\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Admins\Auth\ForgotPasswordController as AdminForgotPasswordController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Users reset password
Route::domain(env('APP_URL'))->group(function () {
    Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('password/reset', [ForgotPasswordController::class, 'reset'])
        ->name('password.update');
});

// Admins reset password
Route::domain('dashboard.' . env('APP_URL'))->group(function () {
    Route::get('password/reset/{token}', [AdminForgotPasswordController::class, 'showResetForm'])
        ->name('admin.password.reset');


    Route::post('password/reset', [AdminForgotPasswordController::class, 'reset'])
        ->name('admins.password.update');
});
