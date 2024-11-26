<?php

namespace App\Http\Controllers\Api\Admins\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Send a reset link to the given admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            // Validate the email
            $request->validate(['email' => 'required|email']);

            // Attempt to send the reset link
            $response = Password::broker('admins')->sendResetLink($request->only('email'));

            if ($response == Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'A password reset link has been successfully sent to your email'
                ], 200);
            } else {
                // Handle the case where the email is not found or link cannot be sent
                return response()->json([
                    'error' => 'We were unable to send a reset link to this email address.'
                ], 400);
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'error' => 'Invalid email address provided.',
                'details' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // Catch all other exceptions
            return response()->json([
                'error' => 'An unexpected error occurred while processing your request.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function showResetForm($token)
    {
        return view('auth.passwords.admins.reset', ['token' => $token]);
    }

    /**
     * Reset the given admin's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        // Validate the token, email, and new password
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Attempt to reset the password
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => trans($status)], 200);
        }

        // Log or debug the actual failure status
        return response()->json([
            'message' => 'Failed to reset password',
            'status' => $status,
        ], 400); // Return the actual status for debugging
    }
}
