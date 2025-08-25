<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $user->createToken('auth_token', ['*'], now()->addHours(2))->plainTextToken;

        return response()->json([
            'status'     => 'success',
            'message'    => 'User registered successfully.',
            'user'       => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // Login user and generate token
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('auth_token', ['*'], now()->addHours(2))->plainTextToken;

        return response()->json([
            'status'     => 'success',
            'message'    => 'Login successful.',
            'user'       => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
    // Logout user (revoke current token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully.'
        ], 200);
    }
}
