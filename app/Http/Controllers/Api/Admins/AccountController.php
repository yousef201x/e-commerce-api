<?php

namespace App\Http\Controllers\Api\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Exception;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function adminInfo(Request $request)
    {
        try {
            return response()->json(['admin' => $request->user()], 200);
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

            // Get Admin id
            $adminId = $request->user()['id'];

            // fetch Admin by id
            $admin = Admin::findOrFail($adminId);

            // update Admin name
            $admin->name = $request->name;
            $admin->Save();

            return response()->json(['success' => 'Admin name updated successfully']);
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
                'email' => 'required|email|unique:Admins,email',
            ]);

            // Get Admin id
            $adminId = $request->Admin()['id'];

            // fetch Admin by id
            $admin = Admin::findOrFail($adminId);

            // update Admin name
            $admin->email = $request->email;
            $admin->Save();

            return response()->json(['success' => 'Email updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
