<?php

namespace App\Http\Controllers;

use App\Event;
use App\RehearsalAttendance;
use App\Rehearsal;
use App\Semester;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RehearsalAttendanceController extends AttendanceController {
    /**
     * RehearsalAttendanceController constructor.
     *
     * Only
     */
    public function __construct() {
        parent::__construct();

        $this->middleware('admin:rehearsal', ['except' => ['excuseSelf', 'confirmSelf']]);
    }

    public function listMissing ($id = null) {
        $rehearsals = Rehearsal::where(
            'start', '<=', Carbon::now()
        )->where(
            'semester', Semester::current()->id
        )->orderBy('start', 'asc')->get();

        if (null === $id || (null === ($rehearsal = Rehearsal::find($id)))) {
            // Get current or last rehearsal.
            $rehearsal = $rehearsals->last();
        }

        if (null === $rehearsal) {
            return back()->withErrors(trans('date.no_last_rehearsal'));
        }

        $users = User::with(['attendances' => function ($query) use ($rehearsal) {
            return $query->where('rehearsal_id', $rehearsal->id)->get();
        }])->get();

        return view('date.rehearsal.listMissing', [
            'currentRehearsal' => $rehearsal,
            'users'     => $users,
            'rehearsals'=> $rehearsals
        ]);
    }

    /**
     * Function for an admin to login if someone attends or is missing.
     *
     * @param Request $request
     * @param Integer $rehearsalId
     * @param Integer $userId
     * @param String $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeAttendance (Request $request, $rehearsalId, $userId, $attendance = null) {
        // Try to get the rehearsal.
        $rehearsal = Rehearsal::find($rehearsalId);

        if (null === $rehearsal) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.rehearsal_not_found')]);
            } else {
                return back()->withErrors(trans('date.rehearsal_not_found'));
            }
        }

        return $this->changeEventAttendance($request, $rehearsal, $userId, $attendance);
    }

    /**
     * Function to change the currently logged in user's attendance for a given rehearsal.
     *
     * @param Request $request
     * @param Integer $rehearsalId
     * @param String $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOwnAttendance (Request $request, $rehearsalId, $attendance) {
        // Try to get the rehearsal.
        $rehearsal = Rehearsal::find($rehearsalId);

        if (null === $rehearsal) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.rehearsal_not_found')]);
            } else {
                return back()->withErrors(trans('date.rehearsal_not_found'));
            }
        }

        return $this->changeOwnEventAttendance($request, $rehearsal, $attendance);
    }

    /**
     * Function to set the currently logged in user to attending for a given rehearsal.
     *
     * @param Request $request
     * @param Integer $rehearsalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmSelf(Request $request, $rehearsalId) {
        return $this->changeOwnAttendance($request, $rehearsalId, \Config::get('enums.attendance')['yes']);
    }

    /**
     * Function to excuse the currently logged in user for a given rehearsal.
     * 
     * @param Request $request
     * @param Integer $rehearsalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function excuseSelf(Request $request, $rehearsalId) {
        return $this->changeOwnAttendance($request, $rehearsalId, \Config::get('enums.attendance')['no']);
    }

    /**
     * Helper to update or create an attendance.
     *
     * @param Event $rehearsal
     * @param User $user
     * @param array $data
     * @return bool
     */
    protected function storeAttendance(Event $rehearsal, User $user, array $data) {
        // Check if we have an attendance for this user/rehearsal.
        $attendance = RehearsalAttendance::where(
            'user_id', $user->id
        )->where(
            'rehearsal_id', $rehearsal->id
        )->first();

        if (null === $attendance) {
            // Make new attendance.
            $attendance = new RehearsalAttendance();

            $attendance->user_id = $user->id;
            $attendance->rehearsal_id = $rehearsal->id;

            // Connect to user and rehearsal via pivot tables.
            $user->attendances()->save($attendance);
            $rehearsal->attendances()->save($attendance);
        }

        // Set attributes accordingly.
        if (isset($data['attendance'])) {
            $attendance->attendance = $data['attendance'];
        }
        if (isset($data['comment'])) {
            $attendance->comment = $data['comment'];
        }
        if (isset($data['internal_comment'])) {
            $attendance->internal_comment = $data['internal_comment'];
        }

        $attendance->missed = $data['missed'];

        return $attendance->save();
    }
}
