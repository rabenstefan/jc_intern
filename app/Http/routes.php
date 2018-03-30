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
    // Include authentication routes automatically.
    Route::auth();

    /**
     * Basic routes
     */
    // The splash screen.
    Route::get('/', 'HomeController@index')->name('index');

    /**
     * Routes for user management.
     */
    Route::resource('users', 'UserController');
    // And for user groups.
    Route::group(['middleware' => 'admin'], function() {
        Route::resource('roles', 'RoleController');
    });

    /**
     * Date controllers (mainly gigs and rehearsals and their attendances)
     * Their index is in the DateController.
     */
    // The index of all dates
    Route::get('dates/{view_type?}', 'DateController@index')->name('dates.index');

    // RESTful resource controller of gigs.
    Route::resource('gigs', 'GigController', [
        'except' => ['index']
    ]);
    // RESTful resource controller of rehearsals.
    Route::resource('rehearsals', 'RehearsalController', [
        'except' => ['index']
    ]);

    // Attendance routes. Setting and getting attendances and so on.
    //
    // This is a bit hacky: Choose the right controller and their action by the parameters, to save repetition and be
    // much more flexible.
    Route::post(
        'attendances/{events_name}/{event_id}/{shorthand?}',
        function (Illuminate\Http\Request $request, $events_name, $event_id, $shorthand = null) {
            // This method will choose the appropriate controller and their action.
            return \App\Http\Controllers\AttendanceController::attendanceRouteSwitch($request, $events_name, $event_id, $shorthand);
        }
    )->where(
        [
            'events_name' => '(gigs|rehearsals)',
            'event_id'    => '[0-9]+',
            'shorthand'   => '(attend|maybe|excuse|change)'
        ]
    )->name('attendances.changeOwnAttendance');
    // Get a list of rehearsal attendances.
    Route::get('rehearsals/attendances/list/{id?}', 'RehearsalAttendanceController@listAttendances')->name('rehearsals.listAttendances');
    // Get a list of gig attendances.
    Route::get('gigs/attendances/list/{id?}', 'GigAttendanceController@listAttendances')->name('gigs.listAttendances');
    // Change if a user was or is present at a rehearsal.
    Route::post('rehearsals/{rehearsal_id}/present/{user_id}', 'RehearsalAttendanceController@changePresence')->name('attendances.changePresence');

    // Routes for external calenders.
    Route::get('calendar_sync', 'DateController@calendarSync')->name('dates.calendarSync');
    Route::get('render_ical', 'DateController@renderIcal')->name('dates.renderIcal');

    /**
     * Music sheet management routes.
     */
    Route::resource('sheets', 'SheetController');
    Route::put('sheets/ajaxUpdate/{id}', 'SheetController@ajaxUpdate');
    Route::get('sheets/user/{id}', 'SheetController@sheetsPerUser');
    Route::get('sheets/{id}/number/{number}', 'SheetController@sheetUser');
    Route::put('sheets/{id}/number/{number}', 'SheetController@sheetUserUpdate');
    Route::get('sheets/{id}/number/{number}/delete', 'SheetController@returnSheet');
    Route::get('sheets/{id}/distribute', 'SheetController@distribute');
    Route::post('sheets/{id}/distribute', 'SheetController@processDistribute');

    /**
     * Helper resources. Mainly for admins.
     */
    Route::post('semesters/new', 'SemesterController@generateNewSemester')->middleware('admin')->name('semesters.generateNew');
});
