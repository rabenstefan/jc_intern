<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Rehearsal;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class AttendanceController extends Controller {
    /**
     * AttendanceController constructor.
     *
     * Only
     */
    public function __construct() {
        $this->middleware('auth');

        $this->middleware('admin:rehearsal', ['except' => 'excuseSelf']);
    }

    /**
     * Function to change the currently logged in user's attendance for a given rehearsal.
     *
     * @param Request $request
     * @param Integer $rehearsalId
     * @param Boolean $attending
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOwnAttendance (Request $request, $rehearsalId, $attending) {
        // Try to get the rehearsal.
        $rehearsal = Rehearsal::find($rehearsalId);

        if (null === $rehearsal) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.rehearsal_not_found')]);
            } else {
                return back()->withErrors(trans('date.rehearsal_not_found'));
            }
        }

        // Get logged in user and prepare data for saving.
        $user = \Auth::user();
        $data = [
            'comment' => $request->has('comment') ? $request->get('comment') : '',
            'internal_comment' => '',
        ];

        // Change the attendance respectively.
        if ($attending) {
            // Confirm attendance of current user for rehearsal with given comments.
            $success = $this->confirmUser($rehearsal, $user, $data);
        } else {
            // Excuse current user for rehearsal with given comments.
            $success = $this->excuseUser($rehearsal, $user, $data);
        }

        // Check if changing the attendance worked.
        if (!$success) {
            $message = $attending ? trans('date.attendance_error') : trans('date.excuse_error');
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => $message]);
            } else {
                return back()->withErrors($message);
            }
        }

        // If we arrive here everything went fine.
        $message = $attending ? trans('date.attendance_saved') : trans('date.excuse_saved');
        if ($request->wantsJson()) {
            return \Response::json(['success' => true, 'message' => $message]);
        } else {
            $request->session()->flash('message_success', $message);
            return back();
        }
    }

    /**
     * Function to excuse the currently logged in user for a given rehearsal.
     *
     * @param Request $request
     * @param Integer $rehearsalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmSelf(Request $request, $rehearsalId) {
        return $this->changeOwnAttendance($request, $rehearsalId, true);
    }

    /**
     * Function to excuse the currently logged in user for a given rehearsal.
     * 
     * @param Request $request
     * @param Integer $rehearsalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function excuseSelf(Request $request, $rehearsalId) {
        return $this->changeOwnAttendance($request, $rehearsalId, false);
    }

    /**
     * Helper to excuse a single user.
     *
     * @param $rehearsal
     * @param $user
     * @param array $data
     * @return bool
     */
    private function excuseUser(Rehearsal $rehearsal, User $user, array $data) {
        $data['excused'] = true;
        $data['missed']  = true;

        return $this->storeAttendance($rehearsal, $user, $data);
    }

    /**
     * Helper to excuse a single user.
     *
     * @param $rehearsal
     * @param $user
     * @param array $data
     * @return bool
     */
    private function confirmUser(Rehearsal $rehearsal, User $user, array $data) {
        $data['excused'] = false;
        $data['missed']  = false;
        $data['comment'] = '';

        return $this->storeAttendance($rehearsal, $user, $data);
    }

    /**
     * Helper to update or create an attendance.
     *
     * @param $rehearsal
     * @param $user
     * @param $data
     * @return bool
     */
    private function storeAttendance(Rehearsal $rehearsal, User $user, array $data) {
        // Check if we have an attendance for this user/rehearsal.
        $attendance = Attendance::where('user_id', $user->id)->where('rehearsal_id', $rehearsal->id)->first();
        if (null === $attendance) {
            // Make new attendance.
            $attendance = new Attendance();

            $attendance->user_id      = $user->id;
            $attendance->rehearsal_id = $rehearsal->id;
        }

        // Set attributes accordingly.
        $attendance->excused   = $data['excused'];
        $attendance->comment   = $data['comment'];
        $attendance->internal_comment = $data['internal_comment'];
        $attendance->missed    = $data['missed'];;

        return $attendance->save();
    }
}
