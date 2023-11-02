<?php

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::delete('logout', 'AuthController@logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['namespace' => 'App\Http\Controllers\Users'], function () {
        Route::get('users', 'UserController@index');
        Route::get('users/current', 'UserController@show');
        Route::patch('users/current', 'UserController@update');

        Route::post('users/current/profile', 'ProfileUserController@store');
        Route::patch('users/current/profile', 'ProfileUserController@update');
        Route::delete('users/current/profile', 'ProfileUserController@destroy');

        Route::post('users/current/address', 'AddressUserController@store');
        Route::get('users/current/address', 'AddressUserController@show');
        Route::patch('users/current/address/{id}', 'AddressUserController@update');
        Route::delete('users/current/address/{id}', 'AddressUserController@destroy');
    });
});
