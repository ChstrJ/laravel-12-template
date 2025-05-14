<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
    Route::delete('/destroy', 'UserController@destroy');
});
