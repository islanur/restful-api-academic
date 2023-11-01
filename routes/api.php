<?php

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('users/{user_id}/profile', 'Users\ProfileUserController@store');
    Route::get('users/{user_id}/profile/{id}', 'Users\ProfileUserController@show');
    Route::patch('users/{user_id}/profile/{id}', 'Users\ProfileUserController@update');
    Route::delete('users/{user_id}/profile/{id}', 'Users\ProfileUserController@destroy');
});
