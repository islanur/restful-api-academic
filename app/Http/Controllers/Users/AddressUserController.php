<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AddressStoreRequest;
use App\Http\Requests\Users\AddressUpdateRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\AddressUser;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddressUserController extends Controller
{
    private function getAddressUser(User $user, int $id): AddressUser
    {
        $addressUser = AddressUser::where('id', $id)->where('user_id', $user->id)->first();
        if (!$addressUser) {
            throw new HttpResponseException(response()->json([
                'errors' => ['message' => 'Not found data address user']
            ])->setStatusCode(404));
        }
        return $addressUser;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        try {
            $address_id = $user->addresuser->id;
            $user->addressuser->findOrFail($address_id);

            $addressUser = new AddressUser($data);
            $addressUser->user_id = $user->id;
            $addressUser->save();

            return (new UserResource(true, 'Success add data address user', $user))
                ->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            return (new UserResource(false, $th->getMessage() . '. Data address already exist, please update.', null))
                ->response()->setStatusCode(404);
        }

        // $user->profileuser;
        // if (!$user->addressuser == null) {
        //     return response()->json([
        //         'message' => 'Already Added'
        //     ]);
        // };



    }

    /**
     * Display the specified resource.
     */
    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->addressuser;

            return (new UserResource(true, 'Success get address user', $user))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new UserResource(true, $th->getMessage(), null))
                ->response()->setStatusCode(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, AddressUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $addressUser = $this->getAddressUser($user, $id);
        $data = $request->validated();

        $addressUser->fill($data);
        $addressUser->save();

        return (new UserResource(true, 'Success update address user', $user, null, $addressUser))
            ->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::user();
        $addressUser = $this->getAddressUser($user, $id);

        $addressUser->delete();

        return (new UserResource(true, 'Success delete profile user', null))
            ->response()->setStatusCode(200);
    }
}
