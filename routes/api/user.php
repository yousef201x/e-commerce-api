<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // Get the authenticated user
    $user = $request->user();

    // Fetch sessions associated with the authenticated user
    $sessions = DB::table('sessions')
        ->where('user_id', $user->id)
        ->get();

    // Return the user data along with the session data
    return response()->json([
        'user' => $user,
        'sessions' => $sessions,
    ]);
});
