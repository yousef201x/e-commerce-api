<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{

    public function userInfo(Request $request)
    {
        try {
            return response()->json(['user' => $request->user()], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function updateName(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:8',
            ]);

            // Get user id
            $userId = $request->user()['id'];

            // fetch user by id
            $user = User::findOrFail($userId);

            // update user name
            $user->name = $request->name;
            $user->Save();

            return response()->json(['success' => 'Username updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function updateEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);

            // Get user id
            $userId = $request->user()['id'];

            // fetch user by id
            $user = User::findOrFail($userId);

            // update user name
            $user->email = $request->email;
            $user->Save();

            return response()->json(['success' => 'Email updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
