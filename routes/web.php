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

// about screen
Route::view('/about', 'about');

// login, register, etc.
Route::auth();

// user calendar
Route::get('/home',                      'HomeController@index')->name('home');
Route::get('/home/next',                 'HomeController@next')->name('home-next');
Route::get('/home/month/{year}/{month}', 'HomeController@month')->name('home-month');
Route::get('/home/week/{year}/{week}',   'HomeController@week')->name('home-week');
Route::get('/home/day/{year}/{day}',     'HomeController@day')->name('home-day');

// edit user profile
Route::get('/profile', 'UserProfileController@read')->name('profile');
Route::post('/profile', 'UserProfileController@update')->name('profileUpdate');


// CRUD Event
Route::resource('event', 'EventController')->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);

// CRUD Group
// Note that the custom routes need to be defined before the resource route
// Otherwise, there is a conflict between /group/[id] and /group/new and you will get a 403 error.
Route::get('/group/new', 'GroupController@participants')->name('participants');
Route::post('/group/new', 'GroupController@addParticipants')->name('addParticipants');
Route::post('/group/leave', 'GroupController@leave')->name('leave-group');
Route::resource('group', 'GroupController')->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::get('/groups', 'GroupController@groups')->name('groups');
