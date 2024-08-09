<?php
use Illuminate\Support\Facades\Route;

Route::post('auth/register', '\App\Http\Controllers\AuthController@register');
Route::post('auth/login', '\App\Http\Controllers\AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('auth/logout', '\App\Http\Controllers\AuthController@logout');

    Route::apiResource('tasks', '\App\Http\Controllers\TaskController');

});
