<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (): JsonResponse {
  return response()->json([
    'status' => false,
    'message' => 'Unauthorized User'
  ], 401);
})->name('login');

Route::controller(AuthController::class)->group(function () {
  Route::post('/users/auth/register', 'register');
  Route::post('/users/auth/login', 'login');
  Route::delete('/users/auth/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
  Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{user}', 'show');
    Route::patch('/users/{user}', 'update');
    Route::patch('/users/{user}/profile', 'updateProfile');
    Route::patch('/users/{user}/address', 'updateAddress');
    Route::delete('/users/{user}', 'destroy');
  });

  Route::controller(DepartmentController::class)->group(function () {
    Route::get('/departments', 'index');
    Route::post('/departments', 'store');
    Route::get('/departments/{department}', 'show');
    Route::patch('/departments/{department}', 'update');
    Route::delete('/departments/{department}', 'destroy');
  });

  Route::controller(InstructorController::class)->group(function () {
    Route::get('/instructors', 'index');
    Route::post('/instructors/create/{user}/profile/{department}', 'store');
    Route::get('/instructors/{instructor}', 'show');
    Route::patch('/instructors/{instructor}', 'update');
    Route::delete('/instructors/{instructor}', 'destroy');
  });
});
