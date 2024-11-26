<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;


class ForgotPasswordController extends Controller
{

    // Send reset link email

    public function sendResetLinkEmail(Request $request)
    {
        try {
            // Validate the email
            $request->validate(['email' => 'required|email']);

            // Attempt to send the reset link
            $response = Password::sendResetLink($request->only('email'));

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
        return view('auth.passwords.users.reset', ['token' => $token]);
    }


    // Reset password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        return $response === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully!'], 200)
            : response()->json(['message' => 'Failed to reset password'], 400);
    }
}
