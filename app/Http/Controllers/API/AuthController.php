<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $credentials = $request->only('username', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid login credentials',
                ], 200);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'access_token' => $token,
            ];
            return response()->json([
                'status' => 200,
                'message' => 'Logged in successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '500',
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function checkLogin(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized. Please log in.',
                'data' => [],
            ], 401);
        }

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        return response()->json([
            'status' => 200,
            'message' => 'User check successful',
            'data' => $data,
        ]);
    }
}