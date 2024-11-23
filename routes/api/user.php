<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::domain(env('APP_URL'))->group(function () {
    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        // Get the authenticated user
        $user = $request->user();

        // Return the user data
        return response()->json([
            'user' => $user,
        ]);
    });
});


// Route::middleware();
