<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
  public function test_register_user_success(): void
  {
    $response = $this->post('/api/users/auth/register', [
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

  public function test_login_success(): void
  {
    $this->test_register_user_success();
    $response = $this->post('/api/users/auth/login', [
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

  public function test_login_invalid_credentials(): void
  {
    User::factory()->create();
    $response = $this->post('/api/users/auth/login', [
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

  public function test_logout_success(): void
  {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->delete('/api/users/auth/logout');
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success logout')
          ->has('data')->where('data', null)
      );
  }
}
