<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
  public function testGetAllUser(): void
  {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->get('/api/users');
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success get all data users')
          ->has('data', 1)
      );;
  }

  public function testGetCurrentUser(): void
  {
    $user = Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->get('/api/users/' . $user->id);
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success get data user')
          ->has('data', 8)
      );;
  }

  public function testUpdateCurrentUserSuccess(): void
  {
    $user = Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->patch('/api/users/' . $user->id, [
      'username' => 'testupdate',
      'email' => 'test@gmail.com'
    ]);
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success update data users')
          ->has(
            'data',
            fn (AssertableJson $json) =>
            $json->where('username', 'testupdate')
              ->where('email', 'test@gmail.com')
              ->hasAny('email_verified_at', 'updated_at', 'created_at', 'id')
          )
      );
  }

  public function testUpdateCurrentUserFailedUsername(): void
  {
    $user = Sanctum::actingAs(User::factory()->create(), ['*']);
    $response = $this->patch('/api/users/' . $user->id, [
      'username' => 'test update',
      'email' => 'test@gmail.com'
    ]);
    $response->assertStatus(400)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has(
          'errors',
          fn (AssertableJson $json) =>
          $json->has('username', 1)
        )
      );
  }

  public function testUpdateProfileUserSuccess(): void
  {
    $user = Sanctum::actingAs(User::factory()->create(), ['*']);
    /** @var \App\Models\User $user **/
    $user->profileuser()->create(['full_name' => $user['email']]);
    $response = $this->patch('/api/users/' . $user->id . '/profile', [
      'full_name' => 'Tes Update Full Name',
    ]);
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success update profile user')
          ->has(
            'data',
            fn (AssertableJson $json) =>
            $json->hasAny('username', 'email', 'email_verified_at', 'updated_at', 'created_at', 'id', 'addressuser')
              ->has(
                'profileuser',
                fn (AssertableJson $json) =>
                $json->hasAny('id', 'id_card_number', 'phone', 'gender', 'religion', 'user_id', 'updated_at', 'created_at')
                  ->where('full_name', 'Tes Update Full Name')
              )
          )
      );
  }

  public function testUpdateAddressUserSuccess(): void
  {
    $user = Sanctum::actingAs(User::factory()->create(), ['*']);
    /** @var \App\Models\User $user **/
    $user->addressuser()->create(['country' => 'Ina']);
    $response = $this->patch('/api/users/' . $user->id . '/address', [
      'country' => 'Indonesia',
      'street' => 'Tekaka'
    ]);
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success update address user')
          ->has(
            'data',
            fn (AssertableJson $json) =>
            $json->hasAny('username', 'email', 'email_verified_at', 'updated_at', 'created_at', 'id', 'profileuser')
              ->has(
                'addressuser',
                fn (AssertableJson $json) =>
                $json->hasAny('id', 'city', 'province', 'postal_code', 'user_id', 'updated_at', 'created_at')
                  ->where('country', 'Indonesia')
                  ->where('street', 'Tekaka')
              )
          )
      );
  }

  public function testDeleteUserSuccess(): void
  {
    $user = Sanctum::actingAs(User::factory()->create(), ['*']);
    /** @var \App\Models\User $user **/
    $user->profileuser()->create(['full_name' => $user['email']]);
    $user->addressuser()->create(['country' => 'Ina']);
    $response = $this->delete('/api/users/' . $user->id);
    $response->assertStatus(200)
      ->assertJson(
        fn (AssertableJson $json) =>
        $json->has('status')->where('status', true)
          ->has('message')->where('message', 'Success delete user account')
          ->has('data')->where('data', null)
      );
  }
}
