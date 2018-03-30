<?php

namespace App\Http\Controllers;

use App\Models\GigAttendance;
use App\Models\Gig;
use App\Models\Semester;
use App\Models\User;
use App\Models\Voice;
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

    //TODO: Make filter gigs possible (via $request?) -> with_old, only XYZ etc.
    public function listAttendances(Request $request) {
        // Get all future gigs of this semester.
        $gigs = Gig::with('gig_attendances')->where(
            'start', '>=', Carbon::now()
        )->where(
            'semester_id', Semester::current()->id
        )->orderBy('start', 'asc')->get(['title', 'start']);

        if (null === $gigs) {
            return back()->withErrors(trans('date.no_gigs_in_future'));
        }

        $voices = Voice::getParentVoices(Voice::getChildVoices()->load('users'));

        return view('date.gig.listAttendances', [
            'gigs'  => $gigs,
            'voices' => $voices,
        ]);
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
