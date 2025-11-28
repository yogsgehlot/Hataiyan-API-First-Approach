<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt with admin guard
        $credentials = $request->only('email', 'password');

        if (!Auth::guard('admin')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        // create personal access token using passport
        $tokenResult = $admin->createToken('Admin Personal Token');
        $token = $tokenResult->accessToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user(); // Admin from admin-api guard
        if ($user) {
            $user->token()->revoke();
        }
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // create other admin accounts
    public function createAdmin(Request $request)
    {
        $this->authorize('create', Admin::class); // optional gate/policy

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8',
            'role' => 'nullable|string'
        ]);

        $admin = Admin::create($request->only('name', 'email', 'password', 'role'));

        return response()->json(['admin' => $admin], 201);
    }
}
