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
            $request->validate(['email' => 'required|email|exists:users,email']);

            // Attempt to send the reset link
            $response = Password::sendResetLink($request->only('email'));

            if ($response == Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => 'A password reset link has been successfully sent to your email'
                ], 200);
            } else {
                // Handle the case where the link cannot be sent
                return response()->json([
                    'error' => 'A server error has occurred. Please try again later.'
                ], 500);
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'error' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            // Catch all other exceptions
            return response()->json([
                'error' => 'An unexpected error occurred while processing your request.',
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
            ? response()->json(['success' => 'Password reset successfully!'], 200)
            : response()->json(['error' => 'Failed to reset password'], 400);
    }
}
