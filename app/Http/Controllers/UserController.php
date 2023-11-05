<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\AddressUpdateRequest;
use App\Http\Requests\Users\ProfileUpdateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\ProfileUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $user = User::get();
        return (new UserResource(true, 'Success get all data users', $user))
            ->response()->setStatusCode(200);
    }

    public function show(User $user): JsonResponse
    {
        try {
            $user = User::findOrFail($user->id);
            return (new UserResource(true, 'Success get data user', $user))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new UserResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(404);
        }
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        try {
            $user = User::findOrFail($user->id);
            $user->fill($request->validated());
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
            $user = User::findOrFail($user->id);
            $user->profileuser->fill($request->validated());
            $user->profileuser->save();
            $user = User::findOrFail($user->id);

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
            $user = User::findOrFail($user->id);
            $user->addressuser->fill($request->validated());
            $user->addressuser->save();
            $user = User::findOrFail($user->id);

            return (new UserResource(true, 'Success update address user', $user))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new UserResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(400);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user = User::findOrFail($user->id);
            $user->profileuser()->delete();
            $user->addressuser()->delete();
            $user->delete();

            return (new UserResource(true, 'Success delete user account', null))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new UserResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(400);
        }
    }
}
