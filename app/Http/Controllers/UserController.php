<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\AddressUpdateRequest;
use App\Http\Requests\Users\ProfileUpdateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): JsonResponse
  {
    $user = User::get();
    return (new UserResource(true, 'Success get all data users', $user))
      ->response()->setStatusCode(200);
  }

  /**
   * Display the specified resource.
   */
  public function show(User $user): JsonResponse
  {
    try {
      $user->profileuser;
      $user->addressuser;
      return (new UserResource(true, 'Success get data user', $user))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(400);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UserUpdateRequest $request, User $user): JsonResponse
  {
    try {
      $data = $request->validated();

      $user->fill($data);
      $user->save();
      return (new UserResource(true, 'Success update data users', $user))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(400);
    }
  }

  public function updateProfile(ProfileUpdateRequest $request, User $user): JsonResponse
  {
    try {
      $data = $request->validated();

      $user->profileuser->fill($data);
      $user->profileuser->save();
      $user->addressuser;

      return (new UserResource(true, 'Success update profile user', $user))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(400);
    }
  }

  public function updateAddress(AddressUpdateRequest $request, User $user): JsonResponse
  {
    try {
      $data = $request->validated();

      $user->addressuser->fill($data);
      $user->profileuser;
      $user->addressuser->save();

      return (new UserResource(true, 'Success update address user', $user))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(400);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $user)
  {
    try {
      //   $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
      $user->profileuser()->delete();
      $user->addressuser()->delete();
      $user->tokens()->delete();
      $user->delete();

      return (new UserResource(true, 'Success delete user account', null))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(400);
    }
  }
}
