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
        Route::get('findByDate/{currentAlbum}', 'NoteController@findByDate');

        Route::get('findById/{id}', 'NoteController@findById');
        Route::post('create', 'NoteController@create');

        Route::delete('delete/{id}', 'NoteController@delete');
        Route::post('create', 'NoteController@create');
        Route::post('update/{id}', 'NoteController@update');
    });

    Route::middleware('JwtRole')->group(function () {
        Route::get('users/admin/findById/{id}', 'UserController@findById');

        Route::get('notes/findAll', 'NoteController@findAll');
        Route::get('users/findAll/{state}', 'UserController@findAll');
        Route::post('users/register/admin', 'UserController@registerAdmin');

    });
    Route::prefix('notes')->group(function () {
        Route::post('upload', 'FileController@upload');
        Route::patch('setProfilePicture', 'FileController@setProfilePicture');
        Route::patch('changeAlbum/{imageId}/{album}', 'FileController@changeAlbum');
        Route::get('findAlbumImages/{album}', 'FileController@findAlbumImages');
        Route::get('findUserImages', 'FileController@findUserImages');
        Route::patch('exchangePhotos/{oldPhoto}/{newPhoto}', 'FileController@exchangePhotos');
        Route::patch('updateAlbumPhotos', 'FileController@updateAlbumPhotos');
    });
});

Route::prefix('users')->group(function () {
    Route::get('findById/{id}', 'UserController@findById');
});
