<?php

namespace App\Http\Controllers;

use App\Services\ValidationRulesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    protected $validationRulesService;

    public function __construct(ValidationRulesService $validationRulesService)
    {
        $this->validationRulesService = $validationRulesService;
    }

    /**
     * Login user and issue tokens without using Auth::attempt().
     */
    public function login(Request $request)
    {
        // Validate the request using ValidationRulesService
        $validated = $request->validate($this->validationRulesService->login());

        // Manually find the user by email
        $user = User::where('email', $validated['email'])->first();

        // Check if the user exists and if the password matches
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate an API token for the authenticated user
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validate the request using ValidationRulesService
        $validated = $request->validate($this->validationRulesService->register());

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Generate a token for the newly registered user
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
        ]);
    }

    /**
     * Logout user and revoke tokens.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        // Revoke all tokens for the user
        $user->tokens()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
