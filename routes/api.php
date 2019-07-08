<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::middleware('auth:api') ->group (function(){
    Route::prefix('users')->group(function () {
        Route::post('logout', 'UserController@logout');    
        Route::delete('delete/{id}', 'UserController@delete');
        Route::post('update/{id}', 'UserController@update');
    });

    Route::prefix('notes')->group(function () {
        Route::get('findById/{id}', 'NoteController@findById');
        Route::delete('delete/{id}', 'NoteController@delete');
        Route::post('create', 'NoteController@create');
        Route::post('update/{id}', 'NoteController@update');
    });

    Route::middleware('JwtRole')->group(function () {
        Route::get('findAll', 'NoteController@findAll');
        Route::get('findAll', 'UserController@findAll');
    });
});

Route::prefix('users')->group(function () {
    Route::get('findById/{id}', 'UserController@findById');
});
