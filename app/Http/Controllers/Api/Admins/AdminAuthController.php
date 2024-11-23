<?php

namespace App\Http\Controllers\Api\Admins;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /**
     * Login Admin and issue tokens without using Auth::attempt().
     */


    public function login(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email|exists:Admins,email',
                'password' => 'required|string|min:8',
            ]);

            // Manually find the Admin by email
            $Admin = Admin::where('email', $validated['email'])->first();

            // Check if the Admin exists and if the password matches
            if (!$Admin || !Hash::check($request->password, $Admin->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Generate an API token for the authenticated Admin
            $token = $Admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Register a new Admin.
     */
    public function register(Request $request)
    {
        try {
            // Validate the request directly in the controller
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:Admins,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create a new Admin
            $Admin = Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Generate a token for the newly registered Admin
            $token = $Admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Registration successful',
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Logout Admin and revoke tokens.
     */
    public function logout(Request $request)
    {
        try {
            $Admin = $request->admin();

            // Revoke all tokens for the Admin
            $Admin->tokens()->delete();

            return response()->json(['message' => 'Logout successful']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
