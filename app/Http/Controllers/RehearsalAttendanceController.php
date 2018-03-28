<?php

namespace App\Http\Controllers;

use App\RehearsalAttendance;
use App\Rehearsal;
use App\Semester;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;
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

    /**
     * Retrieve an event by the given ID.
     *
     * @param $event_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    protected function getEventById ($event_id) {
        // Try to get the rehearsal if it is in the future.
        return Rehearsal::where(
            'id', $event_id
        )->where(
            'start', '>=', Carbon::now()->toDateTimeString()
        )->first();
    }

    /**
     * Add the "missed" parameter to the attendance data.
     *
     * @param Request $request
     * @return array
     */
    protected function prepareAdditionalData(Request $request) {
        $data = parent::prepareAdditionalData($request);

        // Set "missed" to true if request has field which contains string "true".
        $data['missed'] = $request->has('missed') ? $request->get('missed') == 'true' : false;

        return $data;
    }

    /**
     * View shows a list to select which users were actually attending the last rehearsal (optionally: The rehearsal
     * with $rehearsal_id).
     *
     * @param null $rehearsal_id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAttendances ($rehearsal_id = null) {
        $rehearsals = Rehearsal::where(
            'start', '<=', Carbon::now()
        )->where(
            'semester_id', Semester::current()->id
        )->orderBy('start', 'asc')->get();

        if (null === $rehearsal_id || (null === ($rehearsal = Rehearsal::find($rehearsal_id)))) {
            // Get current or last rehearsal.
            $rehearsal = $rehearsals->last();
        }

        if (null === $rehearsal) {
            return back()->withErrors(trans('date.no_last_rehearsal'));
        }

        $users = User::with(['attendances' => function ($query) use ($rehearsal) {
            return $query->where('rehearsal_id', $rehearsal->id)->get();
        }])->get();

        return view('date.rehearsal.listAttendances', [
            'currentRehearsal' => $rehearsal,
            'users'     => $users,
            'rehearsals'=> $rehearsals
        ]);
    }

    public function changePresence(Request $request, $rehearsal_id, $user_id, $missed) {
        // Try to get the rehearsal.
        $rehearsal = Rehearsal::find($rehearsal_id);

        if (null === $rehearsal) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.rehearsal_not_found')]);
            } else {
                return back()->withErrors(trans('date.rehearsal_not_found'));
            }
        }
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
        $rehearsal = Rehearsal::find($rehearsalId)->first();

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
     * Helper to update or create an attendance.
     *
     * @param Rehearsal $rehearsal
     * @param User $user
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    protected function storeAttendance($rehearsal, User $user, array $data) {
        // Update existing or create a new attendance.
        if ($attendance = RehearsalAttendance::updateOrCreate(['user_id' => $user->id, 'rehearsal_id' => $rehearsal->id], $data)) {
            // Connect to user and rehearsal via pivot tables.
            $user->rehearsal_attendances()->syncWithoutDetaching([$attendance->id]);
            $rehearsal->rehearsal_attendances()->syncWithoutDetaching([$attendance->id]);

            return true;
        } else {
            return false;
        }
    }
}
