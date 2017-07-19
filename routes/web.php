<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Authentication Urls
Route::prefix('api')->group(function () {
    Route::post('/login', 'Auth\LoginController@authenticate')->name('test');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
});

// Api Routes
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/user', 'UserController@user')->name('user');

    // Posts
    Route::get('/posts', 'PostController@index');
    Route::post('/posts', 'PostController@store');
    Route::get('/posts/{id}', 'PostController@show');
    Route::post('/posts/{id}', 'PostController@update');
    Route::post('/posts/{id}/delete', 'PostController@destroy');

    // Images
    Route::get('/images', 'UploadController@index');
    Route::post('/images', 'UploadController@store');
    Route::get('/images/{id}', 'UploadController@show');
    Route::post('/images/{id}', 'UploadController@update');
});