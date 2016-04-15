<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
Route::get('/', function () {
    return view('home');
});
*/
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', 'HomeController@index')->name('index');

    Route::resource('user', 'UserController');

    // The index is in the DateController.
    Route::resource('rehearsal', 'RehearsalController', [
        'except' => ['index']
    ]);

    Route::post('rehearsal/attend/{rehearsal_id}', 'AttendanceController@confirmSelf')->name('attendance.confirmSelf');
    Route::post('rehearsal/excuse/{rehearsal_id}', 'AttendanceController@excuseSelf')->name('attendance.excuseSelf');

    Route::resource('gig', 'GigController', [
        'except' => ['index']
    ]);

    Route::get('dates/{view_type?}/', 'DateController@index')->name('date.index');

    Route::group(['middleware' => 'admin'], function() {
        Route::resource('role', 'RoleController');
    });
});
