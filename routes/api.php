<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Unauthenticated routes (no token required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes (token required)
Route::middleware('auth:sanctum')->group(function () {
    // Route to get the authenticated user's profile
    Route::get('/user-profile', [AuthController::class, 'profile']);

    // Route to log out the user (delete their tokens)
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Test route to verify if the user is authenticated (requires valid token)
Route::get('/user', function (Request $request) {
    return $request->user(); // Return the authenticated user
})->middleware('auth:sanctum');
