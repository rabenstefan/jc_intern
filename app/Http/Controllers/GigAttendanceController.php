<?php

namespace App\Http\Controllers;

use App\GigAttendance;
use App\Gig;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GigAttendanceController extends AttendanceController {
    /**
     * GigAttendanceController constructor.
     */
    public function __construct() {
        parent::__construct();

        //TODO: Role management.
        $this->middleware('admin:rehearsal', ['except' => 'changeOwnAttendance']);
    }

    /**
     * Retrieve an event by the given ID.
     *
     * @param $event_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    protected function getEventById ($event_id) {
        // Try to get the gig if it is in the future.
        return Gig::where(
            'id', $event_id
        )->where(
            'start', '>=', Carbon::now()->toDateTimeString()
        )->first();
    }

    /**
     * Store an attendance by looking up the $data['attendance'] in the enums.
     *
     * @param Gig $gig
     * @param User $user
     * @param array $data
     * @return bool
     *
     * @throws \Exception
     */
    protected function storeAttendance($gig, User $user, array $data) {
        // Update existing or create a new attendance.
        return (null !== GigAttendance::updateOrCreate(['user_id' => $user->id, 'gig_id' => $gig->id], $data));
    }
}
