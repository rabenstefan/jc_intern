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

    Route::resource('users', 'UserController');

    // The index is in the DateController.
    Route::resource('rehearsals', 'RehearsalController', [
        'except' => ['index']
    ]);
    Route::get('rehearsals/attendances/list/{id?}', 'RehearsalAttendanceController@listAttendances')->name('attendances.listAttendances');
    Route::post('rehearsals/{rehearsal_id}/attend', 'RehearsalAttendanceController@confirmSelf')->name('attendances.confirmSelf');
    Route::post('rehearsals/{rehearsal_id}/present/{user_id}', 'RehearsalAttendanceController@changeAttendance')->name('attendances.changeAttendance');
    Route::post('rehearsals/{rehearsal_id}/excuse', 'RehearsalAttendanceController@excuseSelf')->name('attendances.excuseSelf');

    Route::resource('gigs', 'GigController', [
        'except' => ['index']
    ]);
    Route::post('gigs/{gig_id}/attendances', 'GigAttendanceController@changeOwnAttendance')->name('commitments.changeOwnAttendance');

    Route::resource('sheets', 'SheetController');
    Route::put('sheets/ajaxUpdate/{id}', 'SheetController@ajaxUpdate');
    Route::get('sheets/user/{id}', 'SheetController@sheetsPerUser');
    Route::get('sheets/{id}/number/{number}', 'SheetController@sheetUser');
    Route::put('sheets/{id}/number/{number}', 'SheetController@sheetUserUpdate');
    Route::get('sheets/{id}/number/{number}/delete', 'SheetController@returnSheet');
    Route::get('sheets/{id}/distribute', 'SheetController@distribute');
    Route::post('sheets/{id}/distribute', 'SheetController@processDistribute');

    Route::get('dates/{view_type?}', 'DateController@index')->name('dates.index');
    Route::get('calendar_sync', 'DateController@calendarSync')->name('dates.calendarSync');
    Route::get('render_ical', 'DateController@renderIcal')->name('dates.renderIcal');

    Route::post('semesters/new', 'SemesterController@generateNewSemester')->name('semesters.generateNew');

    Route::group(['middleware' => 'admin'], function() {
        Route::resource('roles', 'RoleController');
    });
});
