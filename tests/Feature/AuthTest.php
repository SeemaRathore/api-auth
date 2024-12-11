<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // Test registration with valid data
    public function test_register_user_success()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
    }

    // Test registration with missing email
    public function test_register_user_missing_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    // Test login with valid credentials
    public function test_login_success()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    // Test login with invalid credentials
    public function test_login_fail()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    // Test profile access with valid token
    public function test_user_profile_success()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->getJson('/api/user-profile', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['name' => 'John Doe']);
    }

    // Test profile access with invalid token
    public function test_user_profile_fail()
    {
        $response = $this->getJson('/api/user-profile', [
            'Authorization' => 'Bearer invalid_token',
        ]);

        $response->assertStatus(401);
    }

    // Test logout
    public function test_logout_success()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logged out successfully']);
    }
}

