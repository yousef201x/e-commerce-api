<?php

namespace App\Http\Controllers\Api\Users\Socialite;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SocialiteController extends Controller
{
    // Redirect to provider
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Handle provider callback
    public function callback($provider)
    {
        try {
            // Handle the callback from the provider and get the user information
            $user = Socialite::driver($provider)->user();

            // Check if the user already exists in your database
            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // Generate a token for the existing user
                $token = $existingUser->createToken('API Token')->plainTextToken;

                // Return the user and token as a response
                return response()->json([
                    'success' => "Register succesful !",
                    'user' => $existingUser,
                    'token' => $token,
                ]);
            } else {
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Generate a random password
                ]);

                // Create a token for the new user
                $token = $newUser->createToken('API Token')->plainTextToken;


                // Return the user and token
                return response()->json([
                    'success' => "Logged in succesfully !",
                    'user' => $newUser,
                    'token' => $token,
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Authentication Failed !'], 400);
        }
    }
}
