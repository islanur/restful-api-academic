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

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileStoreRequest $request, int $user_id): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            throw new HttpResponseException(response()->json([
                'errors' => ['message' => 'Not found data user']
            ])->setStatusCode(404));
        }

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
    public function show(ProfileUser $profileUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileUser $profileUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfileUser $profileUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfileUser $profileUser)
    {
        //
    }
}
