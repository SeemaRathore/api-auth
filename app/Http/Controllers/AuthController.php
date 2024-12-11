<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register method
    public function register(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json(['message' => 'User registered successfully!'], 201);

        } catch (ValidationException $e) {
            // Catch the validation exception manually
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    // Login method
    public function login(Request $request)
    {
        try {
            // Validate the incoming request
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to log the user in
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid login credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful!',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    // Profile method
    // Profile method (returns user data)
    public function profile(Request $request)
    {
       $userData = auth()->user();
       return response()->json([
           'status' => 'success',
           'message' => 'User profile info',
           'data' => $userData,
       ]);
    }

    // Logout method (deletes all user tokens)
    public function logout()
    {
       auth()->user()->tokens()->delete();
       return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}

