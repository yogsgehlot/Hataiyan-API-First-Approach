<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       
 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('authToken')->accessToken;

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $token,
                'user' => $user,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
     
        // Validate input
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string', // can be email or username
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $identifier = $request->identifier;
        $password = $request->password;

        // Determine if identifier is email or username
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Find user by email or username
        $user = User::where($fieldType, $identifier)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Create Passport token
        $token = $user->createToken('authToken')->accessToken;
 
        return response()->json([
            'status' => true,
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function user()
    {
        return response()->json([
            'status' => true,
            'user' => auth()->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $token_id = Auth::guard('api')->user()->token()->id;
         
        $deleted = Auth::guard('api')->user()->tokens()->where('id', $token_id)->delete();
        if(!$deleted){
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
            ], 500);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }

}
