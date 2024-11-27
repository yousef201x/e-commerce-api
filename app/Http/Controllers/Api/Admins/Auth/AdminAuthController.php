<?php

namespace App\Http\Controllers\Api\Admins\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

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
            $admin = Admin::where('email', $validated['email'])->first();

            // Check if the Admin exists and if the password matches
            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Generate an API token for the authenticated Admin
            $token = $admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => 'Login successful',
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Server Error'
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
            $admin = Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Generate a token for the newly registered Admin
            $token = $admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => 'Registration successful',
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Server Error'
            ], 500);
        }
    }

    /**
     * Logout Admin and revoke tokens.
     */
    public function logout(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            if (!$admin) {
                return response()->json(['message' => 'Admin not authenticated'], 401);
            }

            // Revoke all tokens for the Admin
            $admin->tokens->each(function ($token) {
                $token->delete();
            });


            return response()->json(['success' => 'Logout successful'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'server Error'], 500);
        }
    }
}
