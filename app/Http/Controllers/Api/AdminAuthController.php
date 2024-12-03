<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Requests\AdminLoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\AdminRegisterRequest;

class AdminAuthController extends Controller
{

    public function register(AdminRegisterRequest $request) {
        $validator =$request->validated();
        $validator['password'] = bcrypt($validator['password']);
        $admin = Admin::create($validator);
        return response()->json([
            'message' => ' successfully registered',
            'data'=>new AdminResource($admin),
        ], 201);
    }

    public function login(AdminLoginRequest $request)
    {
        $validator =$request->validated();

        $validator = $request->only('email','password');

        $email = $validator['email'] ?? null;
        $password = $validator['password'] ?? null;

        // Check for required fields
        if (empty($email)) {
            return response()->json(['error' => 'The email field is required.'], 422);
        }

        if (empty($password)) {
            return response()->json(['error' => 'The password field is required.'], 422);
        }

        // Check if the admin exists
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return response()->json(['error' => 'the email is invalid.'], 404);
        }

        // Attempt to login with the provided credentials
        if (!$token = auth()->guard('admin')->attempt($validator)) {
            return response()->json(['error' => 'Invalid password. Please try again.'], 401);
        }

        $admin = auth()->guard('admin')->user();

        return response()->json([
            'message' => 'Login successful',
            'data'=>new AdminResource($admin),
            'access_token' => $token,

        ],200);
    }

    public function refreshToken()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $token]);
        } catch (JWTException ) {
            return response()->json(['error' => 'token_invalid'], 401);
        }
    }

    public function logout()
    {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not log out, please try again'], 500);
        }
    }
}
