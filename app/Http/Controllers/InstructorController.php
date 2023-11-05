<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstructorRequest;
use App\Models\Instructor;
use App\Http\Requests\UpdateInstructorRequest;
use App\Http\Resources\Users\InstructorResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class InstructorController extends Controller
{
  public function index()
  {
    try {
      $instructor = Instructor::with(['user' => ['AddressUser', 'profileUser'], 'department'])->get();

      return (new UserResource(true, 'Success get all data instructors', $instructor))
        ->response()->setStatusCode(201);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(404);
    }
  }

  public function store(User $user, Department $department, StoreInstructorRequest $request): JsonResponse
  {
    try {
      $user = User::findOrFail($user->id);
      $dept = Department::findOrFail($department->id);

      $instructor = Instructor::where('user_id', $user->id)->first();
      if ($instructor) {
        return (new UserResource(false, 'Data already exist', null))
          ->response()->setStatusCode(400);
      }

      $instructor = new Instructor();
      $instructor->fill($request->validated());
      $instructor->user()->associate($user);
      $instructor->department()->associate($dept);
      $instructor->save();

      $user = User::where('id', $user->id)->with('instructor.department')->first();

      return (new UserResource(true, 'Success create data instructor', $user))
        ->response()->setStatusCode(201);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(404);
    }
  }

  public function show(Instructor $instructor)
  {
    try {
      $instructor = Instructor::findOrFail($instructor->id);
      $user = User::where('id', $instructor->user_id)->with('instructor.department')->first();

      return (new UserResource(true, 'Success get data instructor', $user))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(404);
    }
  }

  public function update(UpdateInstructorRequest $request, Instructor $instructor)
  {
    try {
      $instructor = Instructor::findOrFail($instructor->id);

      $instructor->fill($request->validated());
      $instructor->save();

      $instructor = Instructor::where('id', $instructor->id)
        ->with(['user.profileUser', 'user.addressUser', 'department'])
        ->first();

      return (new UserResource(true, 'Success udpate data instructor', $instructor))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(404);
    }
  }

  public function destroy(Instructor $instructor)
  {
    try {
      $instructor = Instructor::findOrFail($instructor->id);
      $instructor->delete();

      return (new UserResource(true, 'Success delete data instructor', null))
        ->response()->setStatusCode(200);
    } catch (\Throwable $th) {
      return (new UserResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(404);
    }
  }
}
