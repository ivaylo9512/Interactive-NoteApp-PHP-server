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


Route::middleware('auth:api') ->group (function(){
});

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::get('findUsers', 'UserController@findAll');
Route::get('findUser/{id}', 'UserController@findById');
Route::delete('delete/{id}', 'UserController@delete');
Route::post('create', 'UserController@create');
Route::post('update/{id}', 'UserController@update');


Route::get('findNotes', 'NoteController@findAll');
Route::get('findNote/{id}', 'NoteController@findById');
Route::delete('delete/{id}', 'NoteController@delete');
Route::post('create', 'NoteController@create');
Route::post('update/{id}', 'NoteController@update');

