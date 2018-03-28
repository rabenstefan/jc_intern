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

        $this->middleware('admin:rehearsal', ['except' => ['changeOwnRehearsalAttendance', 'changeOwnRehearsalComment']]);
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
     * @param String $missing
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeMissing (Request $request, $rehearsalId, $userId, $missing = null) {
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


    public function changeOwnRehearsalAttendance(Request $request, $rehearsal_id) {
        $rehearsal = Rehearsal::find($rehearsal_id);

        return $this->changeOwnEventAttendance($request, $rehearsal);
    }

    public function changeOwnRehearsalComment(Request $request, $rehearsal_id) {
        $rehearsal = Rehearsal::find($rehearsal_id);

        return $this->changeOwnEventComment($request, $rehearsal);
    }

    /**
     * Helper to update or create an attendance.
     *
     * @param $rehearsal
     * @param User $user
     * @param array $data
     * @return bool
     */
    protected function storeAttendance($rehearsal, User $user, array $data) {
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

        if (isset($data['missed'])) {
            $attendance->missed = $data['missed'];
        }

        return $attendance->save();
    }
}
