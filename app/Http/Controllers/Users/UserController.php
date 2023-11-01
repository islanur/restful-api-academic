<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
    public function show(): JsonResponse
    {
        $user = Auth::user();
        return (new UserResource(true, 'Success get data users', $user))
            ->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        /** @var \App\Models\User $user **/
        $user->fill($data);
        $user->save();
        return (new UserResource(true, 'Success update data users', $user))
            ->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
