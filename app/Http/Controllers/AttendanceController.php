<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Illuminate\Http\Request;

abstract class AttendanceController extends Controller {
    /**
     * RehearsalAttendanceController constructor.
     *
     * Only
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Shorthand "I will attend $event_id" for routes.
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function attendSelf(Request $request, $event_id) {
        return $this->changeOwnAttendance($request, $event_id, 'yes');
    }

    /**
     * Shorthand "I might attend $event_id" for routes.
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function maybeSelf(Request $request, $event_id) {
        return $this->changeOwnAttendance($request, $event_id, 'maybe');
    }

    /**
     * Shorthand "I won't attend $event_id" for routes.
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function excuseSelf(Request $request, $event_id) {
        return $this->changeOwnAttendance($request, $event_id, 'no');
    }

    /**
     * Change the own attendance for given $event_id.
     *
     * @param Request $request
     * @param $event_id
     * @param null $attending
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public abstract function changeOwnAttendance(Request $request, $event_id, $attending = null);

    /**
     * Function to change if user with $user_id will attend.
     *
     * @param Request $request
     * @param Event $event
     * @param Integer $user_id
     * @param String $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeEventAttendance (Request $request, $event, $user_id, $attendance = null) {
        if (null === $event) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.event_not_found')]);
            } else {
                return back()->withErrors(trans('date.event_not_found'));
            }
        }

        // Try to get the user.
        if (null === $user_id) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.user_not_given')]);
            } else {
                return back()->withErrors(trans('date.user_not_given'));
            }
        }

        $user = User::find($user_id);

        if (null === $user) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.user_not_found')]);
            } else {
                return back()->withErrors(trans('date.user_not_found'));
            }
        }

        if (null === $attendance && $request->has('attendance')) {
            $attendance = $request->get('attendance');
        } else {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.attendance_not_given')]);
            } else {
                return back()->withErrors(trans('date.attendance_not_given'));
            }
        }

        return $this->changeUserEventAttendance($request, $event, $user, $attendance);
    }

    /**
     * Function to change the currently logged in user's attendance for a given rehearsal.
     *
     * @param Request $request
     * @param Event $event
     * @param Boolean $attending
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOwnEventAttendance (Request $request, $event, $attending) {
        if (null === $event) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.event_not_found')]);
            } else {
                return back()->withErrors(trans('date.event_not_found'));
            }
        }

        // Get logged in user and prepare data for saving.
        $user = \Auth::user();
        return $this->changeUserEventAttendance($request, $event, $user, $attending);
    }

    /**
     * Change event attendance for given User.
     *
     * @param Request $request
     * @param $event
     * @param User $user
     * @param $attendance
     * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private function changeUserEventAttendance(Request $request, $event, User $user, $attendance) {
        $data = [
            'comment' => $request->has('comment') ? $request->get('comment') : '',
            'internal_comment' => $request->has('internal_comment') ? $request->get('internal_comment') : '',
            'attendance' => $attendance,
        ];

        // Change the attendance respectively.
        $success = $this->storeAttendance($event, $user, $data);

        // Check if changing the attendance worked.
        if (!$success) {
            $message = 'yes' == $attendance ? trans('date.attendance_error') : trans('date.excuse_error');
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => $message]);
            } else {
                return back()->withErrors($message);
            }
        }

        // If we arrive here everything went fine.
        $message = 'yes' == $attendance ? trans('date.attendance_saved') : trans('date.excuse_saved');
        if ($request->wantsJson()) {
            return \Response::json(['success' => true, 'message' => $message]);
        } else {
            $request->session()->flash('message_success', $message);
            return back();
        }
    }

    /**
     * Helper to update or create an attendance.
     *
     * @param $event
     * @param User $user
     * @param array $data
     * @return bool
     */
    abstract protected function storeAttendance($event, User $user, array $data);

    /**
     * This function simplifies the routing by choosing the appropriate controller and function to call.
     *
     * @param Request $request
     * @param $events_name
     * @param $event_id
     * @param null $shorthand
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public static function attendanceRouteSwitch(Request $request, $events_name, $event_id, $shorthand = null) {
        // The right controller gets chosen based on the event name (in plural for routes!).
        $controller = null;

        if ($events_name == 'gigs') {
            $controller = new GigAttendanceController();
        } else if ($events_name == 'rehearsals') {
            $controller = new RehearsalAttendanceController();
        } else {
            // Not a known type of event.
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.route_invalid')]);
            } else {
                return back()->withErrors(trans('date.route_invalid'));
            }
        }

        // We have the right controller now, choose the right method, too.
        switch ($shorthand) {
            case 'attend':
                return $controller->attendSelf($request, $event_id);
                break;
            case 'maybe':
                return $controller->maybeSelf($request, $event_id);
                break;
            case 'excuse':
                return $controller->excuseSelf($request, $event_id);
                break;
            default:
            case 'change':
                return $controller->changeOwnAttendance($request, $event_id);
                break;
        }
    }
}
