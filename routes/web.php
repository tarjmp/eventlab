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

// search functionality
Route::get('/search', 'SearchController@search')->name('search');

// user calendar
Route::get('/home',                      'HomeController@index')->name('home');
Route::get('/home/next',                 'HomeController@next')->name('home-next');

// each calendar view can be called with or without a specified date
Route::get('/home/month/{year}/{month}', 'HomeController@month')->name('home-month-param')->where(['year' => '[0-9]{1,4}', 'month' => '[0-9]{1,2}']);
Route::get('/home/month',                'HomeController@month')->name('home-month');

Route::get('/home/day/{year}/{month}/{day}',  'HomeController@day')->name('home-day-param')->where(['year' => '[0-9]{1,4}', 'month' => '[0-9]{1,2}', 'day' => '[0-9]{1,2}']);
Route::get('/home/day',                       'HomeController@day')->name('home-day');

// edit user profile
Route::get('/profile', 'UserProfileController@read')->name('profile');
Route::post('/profile', 'UserProfileController@update')->name('profileUpdate');

// event notifications
Route::get('/notifications', 'EventController@replies')->name('notifications');
Route::post('/notifications/{event}', 'EventController@updateReplies')->name('notificationsUpdate');


// CRUD Event
Route::resource('event', 'EventController')->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);

Route::get('/event/{id}/list', 'EditWhatToBringListController@show')->name('list');
Route::get('event/{id}/list/edit', 'EditWhatToBringListController@edit')->name('listEdit');
Route::post('/event/{id}/list/edit', 'EditWhatToBringListController@store')->name('listStore');

// Chat messages for events
Route::post('/message/add', 'MessageController@add')->name('add-message');
Route::post('/message/delete', 'MessageController@delete')->name('delete-message');
Route::post('/message/get', 'MessageController@get')->name('get-messages');

// CRUD Group
// Note that the custom routes need to be defined before the resource route
// Otherwise, there is a conflict between /group/[id] and /group/new and you will get a 403 error.
Route::get('/group/new', 'GroupController@participants')->name('participants');
Route::post('/group/new', 'GroupController@addParticipants')->name('addParticipants');
Route::get('/group/{id}/update', 'GroupController@newParticipants')->name('newParticipants');
Route::post('/group/{id}/update', 'GroupController@addNewParticipants')->name('addNewParticipants');
Route::post('/group/leave', 'GroupController@leave')->name('leave-group');
Route::resource('group', 'GroupController')->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::get('/groups', 'GroupController@groups')->name('groups');

//Manage subscriptions
Route::get('/managesubscriptions', 'ManageSubscriptionsController@show')->name('UpdateSubscriptions');
Route::post('/managesubscriptions', 'ManageSubscriptionsController@update')->name('UpdateSubscriptions');
