<?php

namespace App\Http\Controllers;

use App\GigAttendance;
use App\Gig;
use App\User;
use Illuminate\Http\Request;

class GigAttendanceController extends AttendanceController {
    /**
     * RehearsalAttendanceController constructor.
     *
     * Only
     */
    public function __construct() {
        $this->middleware('auth');

        $this->middleware('admin:rehearsal', ['except' => ['changeOwnGigAttendance', 'changeOwnGigComment']]);
    }

    public function changeOwnGigAttendance(Request $request, $gig_id) {
        // Try to get the gig.
        $gig = Gig::find($gig_id);

        return $this->changeOwnEventAttendance($request, $gig);
    }

    public function changeOwnGigComment(Request $request, $gig_id) {
        // Try to get the gig.
        $gig = Gig::find($gig_id);

        return $this->changeOwnEventComment($request, $gig);
    }

    /**
     * Store an attendance by looking up the $data['attendance'] in the enums.
     *
     * @param Gig $gig
     * @param User $user
     * @param array $data
     * @return bool
     */
    protected function storeAttendance($gig, User $user, array $data) {
        // Check if we have a commitment for this user/rehearsal.
        $commitment = GigAttendance::where('user_id', $user->id)->where('gig_id', $gig->id)->first();

        if (null === $commitment) {
            // Make new commitment.
            $commitment = new GigAttendance();

            $commitment->user_id = $user->id;
            $commitment->gig_id = $gig->id;
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
