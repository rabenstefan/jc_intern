<?php

namespace App\Http\Controllers;

use App\GigAttendance;
use App\Gig;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GigAttendanceController extends Controller {
    /**
     * GigAttendanceController constructor.
     */
    public function __construct() {
        $this->middleware('auth');

        //TODO: Role management.
        $this->middleware('admin:rehearsal', ['except' => 'changeOwnAttendance']);
    }

    /**
     * Function to set a commitment (with appropriate status) for the current user.
     *
     * @param Request $request
     * @param $gig_id
     * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeOwnAttendance(Request $request, $gig_id) {
        // Try to get the gig if it is in the future.
        $gig = Gig::find($gig_id)->where('start', '<=', Carbon::now()->toDateString());

        if (null === $gig) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.gig_not_found')]);
            } else {
                return back()->withErrors(trans('date.gig_not_found'));
            }
        }

        // Get logged in user and prepare data for saving.
        $user = \Auth::user();
        $data = [
            'attendance' => $request->has('attendance') ? $request->get('attendance') : null,
            'comment' => $request->has('comment') ? $request->get('comment') : null,
            'internal_comment' => null,
        ];

        // Change the commitment respectively.
        $success = $this->storeAttendance($gig, $user, $data);

        // Check if changing the attendance worked.
        if (!$success) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.commitment_change_error')]);
            } else {
                return back()->withErrors(trans('date.commitment_change_error'));
            }
        }

        // If we arrive here everything went fine.
        if ($request->wantsJson()) {
            return \Response::json(['success' => true, 'message' => trans('date.commitment_change_success')]);
        } else {
            $request->session()->flash('message_success', trans('date.commitment_change_success'));
            return back();
        }
    }

    /**
     * Store an attendance by looking up the $data['attendance'] in the enums.
     *
     * @param Gig $gig
     * @param User $user
     * @param array $data
     * @return bool
     */
    private function storeAttendance(Gig $gig, User $user, array $data) {
        // Check if we have a attendance for this user/gig.
        $attendance = GigAttendance::where(
            'user_id', $user->id
        )->where(
            'gig_id', $gig->id
        )->first();

        if (null === $attendance) {
            // Make new attendance.
            $attendance = new GigAttendance();

            $attendance->user_id = $user->id;
            $attendance->gig_id = $gig->id;

            // Connect to user and rehearsal via pivot tables.
            $user->attendances()->save($attendance);
            $gig->gig_attendances()->save($attendance);
        }

        // Set attributes accordingly.
        if (isset($data['attendance'])) {
            $config = \Config::get('enums.attendances');

            if (!isset($config[$data['attendance']])) {
                //TODO: Cleanup related pivot entries.
                return false;
            }

            $attendance->attendance = $config[$data['attendance']];
        }
        if (isset($data['comment'])) {
            $attendance->comment = $data['comment'];
        }
        if (isset($data['internal_comment'])) {
            $attendance->internal_comment = $data['internal_comment'];
        }

        return $attendance->save();
    }
}
