<?php

namespace App\Http\Controllers;

use App\GigAttendance;
use App\Gig;
use App\User;
use Illuminate\Http\Request;

class GigAttendanceController extends Controller {
    /**
     * RehearsalAttendanceController constructor.
     *
     * Only
     */
    public function __construct() {
        $this->middleware('auth');

        $this->middleware('admin:rehearsal', ['except' => 'commitSelf']);
    }

    /**
     * Function to set a commitment (with appropriate status) for the current user.
     *
     * @param Request $request
     * @param $gig_id
     * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function commitSelf(Request $request, $gig_id) {
        // Try to get the gig.
        $gig = Gig::find($gig_id);

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
            'comment' => $request->has('comment') ? $request->get('comment') : '',
            'attendance' => $request->has('attendance') ? $request->get('attendance') : 'no',
            'internal_comment' => '',
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
        // Check if we have a commitment for this user/rehearsal.
        $commitment = GigAttendance::where('user_id', $user->id)->where('gig_id', $gig->id)->first();

        if (null === $commitment) {
            // Make new commitment.
            $commitment = new GigAttendance();

            $commitment->user_id = $user->id;
            $commitment->gig_id = $gig->id;

            // Connect to user and rehearsal via pivot tables.
            $user->attendances()->save($commitment);
            $gig->commitments()->save($commitment);
        }

        // Set attributes accordingly.
        if (isset($data['comment'])) {
            $commitment->comment = $data['comment'];
        }
        if (isset($data['internal_comment'])) {
            $commitment->internal_comment = $data['internal_comment'];
        }

        $config = \Config::get('enums.attendances');
        if (!isset($data['attendance']) || !isset($config[$data['attendance']])) {
            return false;
        }

        $commitment->attendance = $config[$data['attendance']];

        return $commitment->save();
    }
}
