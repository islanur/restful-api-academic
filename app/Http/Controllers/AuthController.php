<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\AddressUser;
use App\Models\ProfileUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function register(AuthRegisterRequest $request): JsonResponse
  {
    try {
      $user = User::create($request->validated());

      $profile = new ProfileUser();
      $profile->user()->associate($user);
      $profile->save();

      $address = new AddressUser();
      $address->user()->associate($user);
      $address->save();

      $user = User::find($user->id);
      $role = Role::where('name', 'guest')->first();
      $user->roles()->attach($role);

      $token = $user->createToken('user-token', ['guest'])->plainTextToken;

      return (new AuthResource(true, 'Success register user', $user, $token))
        ->response()->setStatusCode(201);
    } catch (\Throwable $th) {
      return (new AuthResource(true, $th->getMessage(), null))
        ->response()->setStatusCode(404);
    }
  }

  public function login(AuthLoginRequest $request): JsonResponse
  {
    $user = $request->validated();

    if (!Auth::attempt(['email' => $user['email'], 'password' => $user['password']])) {
      return (new AuthResource(false, 'Invalid Credentials', null))
        ->response()->setStatusCode(401);
    }

    /** @var \App\Models\User $user **/
    $user = Auth::user();
    $user = User::where('id', $user->id)->without('profileUser', 'addressUser')->first();

    return (new AuthResource(true, 'Success login', $user))
      ->response()->setStatusCode(200);
  }

  public function logout(): JsonResponse
  {
    /** @var \App\Models\User $user **/
    $user = Auth::user();
    $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

    return (new AuthResource(true, 'Success logout', null))
      ->response()->setStatusCode(200);
  }
}
