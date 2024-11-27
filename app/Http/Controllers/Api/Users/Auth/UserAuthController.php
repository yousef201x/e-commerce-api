<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class UserAuthController extends Controller
{
    /**
     * Login user and issue tokens without using Auth::attempt().
     */


    public function login(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8',
            ]);

            // Manually find the user by email
            $user = User::where('email', $validated['email'])->first();

            // Check if the user exists and if the password matches
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Generate an API token for the authenticated user
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
            ], 500);
        }
    }


    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        try {
            // Validate the request directly in the controller
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create a new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Generate a token for the newly registered user
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => 'Registration successful',
                'user' => $user,
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    /**
     * Logout user and revoke tokens.
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            // Revoke all tokens for the user
            $user->tokens()->delete();

            return response()->json(['success' => 'Logout successful']);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
