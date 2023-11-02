<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TestAuth extends TestCase
{
    public function testRegisterUserSuccess(): void
    {
        $response = $this->post('/api/register', [
            'username' => 'Test1',
            'email' => 'test1@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')->where('status', true)
                    ->has('message')->where('message', 'Success register user')
                    ->has('data', 5)
            );
    }

    public function testRegisterUserAlreadyExist(): void
    {
        $this->testRegisterUserSuccess();
        $response = $this->post('/api/register', [
            'username' => 'Test1',
            'email' => 'test1@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => ["The username has already been taken."],
                    'email' => ["The email has already been taken."]
                ]
            ]);
    }

    public function testRegisterUserPasswordMatch(): void
    {
        $response = $this->post('/api/register', [
            'username' => 'Test1',
            'email' => 'test1@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'password' => ["The password field confirmation does not match."]
                ]
            ]);
    }

    public function testLoginSuccess(): void
    {
        $this->testRegisterUserSuccess();
        $response = $this->post('/api/login', [
            'email' => 'test1@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')->where('status', true)
                    ->has('message')->where('message', 'Success login')
                    ->has('data', 6)
                    ->has('token')
            );
    }

    public function testLoginInvalidCredentials(): void
    {
        $this->testRegisterUserSuccess();
        $response = $this->post('/api/login', [
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(401)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')->where('status', false)
                    ->has('message')->where('message', 'Invalid Credentials')
                    ->has('data')->where('data', null)
            );
    }

    public function testLoginInvalid(): void
    {
        $this->testRegisterUserSuccess();
        $response = $this->post('/api/login', [
            'email' => 'test',
            'password' => '123456',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'email' => ["The email field must be a valid email address."]
                ]
            ]);
    }
}
