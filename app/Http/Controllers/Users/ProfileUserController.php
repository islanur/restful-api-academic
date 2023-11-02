<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ProfileStoreRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\ProfileUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = Auth::user();

            $profileUser = new ProfileUser($data);
            $profileUser->user_id = $user->id;
            $profileUser->save();

            $user->profileuser;

            return (new UserResource(true, 'Success add profile to user', $user))
                ->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            return (new UserResource(true, $th->getMessage(), null))
                ->response()->setStatusCode(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = Auth::user();
            $user->profileuser->fill($data);

            $user->profileuser;
            $user->addressuser;

            return (new UserResource(true, 'Success update profile user', $user))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new UserResource(true, $th->getMessage(), null))
                ->response()->setStatusCode(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        try {
            $user = Auth::user();

            $user->profileuser->delete();

            return (new UserResource(true, 'Success delete profile user', null))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new UserResource(true, 'User profile data is empty. ' . $th->getMessage(), null))
                ->response()->setStatusCode(404);
        }
    }
}
