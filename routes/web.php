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

// enable https for all non-localhost instances
if (!App::environment('local')) {
    URL::forceScheme('https');
}

// home screen
Route::view('/', 'welcome');

// login, register, etc.
Route::auth();

// user calendar
Route::get('/home', 'HomeController@index')->name('home');

// edit user profile
Route::get('/profile', 'UserProfileController@read')->name('profile');
Route::post('/profile', 'UserProfileController@update')->name('profileUpdate');

// crud event
Route::resource('event', 'EventController')->only(['create', 'store', 'show', 'edit']);