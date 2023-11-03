<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());
            $user->profileuser()->create(['full_name' => $user['email']]);
            $user->addressuser()->create(['country' => 'Indonesia']);

            return (new AuthResource(true, 'Success register user', $user))
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
        $token = $user->createToken('Guest')->plainTextToken;

        return (new AuthResource(true, 'Success login', $user, $token))
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
