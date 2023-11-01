<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ProfileStoreRequest;
use App\Http\Resources\Users\ProfileResource;
use App\Models\ProfileUser;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileUserController extends Controller
{
    private function getUser(int $user_id): User
    {
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            throw new HttpResponseException(response()->json([
                'errors' => ['message' => 'Not found data user']
            ])->setStatusCode(404));
        }
        return $user;
    }

    private function getProfileUser(User $user, int $id): ProfileUser
    {
        $profileUser = ProfileUser::where('id', $id)->where('user_id', $user->id)->first();
        if (!$profileUser) {
            throw new HttpResponseException(response()->json([
                'errors' => ['message' => 'Not found data profile user']
            ])->setStatusCode(404));
        }
        return $profileUser;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $user_id, ProfileStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->getUser($user_id);

        if (ProfileUser::where('user_id', $user->id)->count() == 1) {
            throw new HttpResponseException(response([
                'errors' => [
                    'email' => ['Profile user already added.']
                ]
            ], 401));
        }

        $profileUser = new ProfileUser($data);
        $profileUser->user_id = $user->id;
        $profileUser->save();

        return (new ProfileResource(true, 'Success add profile to user', $profileUser, $user))
            ->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $user_id, int $id): JsonResponse
    {
        $user = $this->getUser($user_id);
        $profileUser = $this->getProfileUser($user, $id);

        return (new ProfileResource(true, 'Success get profile user', $profileUser, $user))
            ->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $user_id, int $id, ProfileStoreRequest $request): JsonResponse
    {
        $user = $this->getUser($user_id);
        $profileUser = $this->getProfileUser($user, $id);
        $data = $request->validated();

        $profileUser->fill($data);
        $profileUser->save();

        return (new ProfileResource(true, 'Success update profile user', $profileUser, $user))
            ->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user_id, int $id): JsonResponse
    {
        $user = $this->getUser($user_id);
        $profileUser = $this->getProfileUser($user, $id);

        $profileUser->delete();

        return (new ProfileResource(true, 'Success delete profile user', null, null))
            ->response()->setStatusCode(200);
    }
}
