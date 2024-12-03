<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentLoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\StudentRegisterRequest;
use App\Notifications\LoginNotification;

class StudentAuthController extends Controller
{
    public function register(StudentRegisterRequest $request)
    {
        $validator =$request->validated();
        $validator['password'] = bcrypt($validator['password']);
        $Student = Student::create($validator);
        return response()->json([
            'message' => ' successfully registered',
            'data'=>new StudentResource($Student),
        ], 201);
    }

    public function login(StudentLoginRequest $request)
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

        // Check if the student exists
        $student = Student::where('email', $email)->first();

        if (!$student) {
            return response()->json(['error' => 'the email is invalid.'], 404);
        }

        // Attempt to login with the provided credentials
        if (!$token = auth()->guard('student')->attempt($validator)) {
            return response()->json(['error' => 'Invalid password. Please try again.'], 401);
        }
         // Notify the student on successful login
   /*
    $student->notify(new LoginNotification());
 */
    $student = auth()->guard('student')->user();

        return response()->json([
            'message' => 'Login successful',
            'data'=>new StudentResource($student),
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
