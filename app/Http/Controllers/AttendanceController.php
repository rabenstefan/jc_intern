<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Carbon\Carbon;
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
     * Function for an admin to login if someone attends or is missing.
     *
     * @param Request $request
     * @param Event $event
     * @param Integer $userId
     * @param String $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeEventAttendance (Request $request, $event, $userId, $attendance = null) {
        if (null === $event) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.event_not_found')]);
            } else {
                return back()->withErrors(trans('date.event_not_found'));
            }
        }

        // Try to get the user.
        if (null === $userId) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.user_not_given')]);
            } else {
                return back()->withErrors(trans('date.user_not_given'));
            }
        }

        $user = User::find($userId);

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

        // Store the attendance for found event and user.
        if (!$this->storeAttendance($event, $user, ['attendance' => $attendance])) {
            // Did not work.
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.attendance_error')]);
            } else {
                return back()->withErrors(trans('date.attendance_error'));
            }
        }

        // If we arrive here everything went fine.
        $message = $attendance ? trans('date.attendance_saved') : trans('date.excuse_saved');
        if ($request->wantsJson()) {
            return \Response::json(['success' => true, 'message' => $message]);
        } else {
            $request->session()->flash('message_success', $message);
            return back();
        }
    }

    public function changeOwnEventComment (Request $request, $event) {
        if (null === $event) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.event_not_found')]);
            } else {
                return back()->withErrors(trans('date.event_not_found'));
            }
        }

        $success = false;
        if ($request->has('comment')) {
            $attendance = $event->current_user_attendance;
            var_dump($attendance);
            $attendance->comment = $request->has('comment') ? $request->get('comment') : '';
            $success = $attendance->save();
        }


        // Check if changing the attendance worked.
        if (!$success) {
            $message = trans('date.change_own_attendance_comment_error');
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => $message]);
            } else {
                return back()->withErrors($message);
            }
        }

        // If we arrive here everything went fine.
        $message =trans('date.own_attendance_comment_saved');
        if ($request->wantsJson()) {
            return \Response::json(['success' => true, 'message' => $message]);
        } else {
            $request->session()->flash('message_success', $message);
            return back();
        }
    }

    /**
     * Function to change the currently logged in user's attendance for a given event.
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOwnEventAttendance (Request $request, $event)
    {
        if (null === $event) {
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => trans('date.event_not_found')]);
            } else {
                return back()->withErrors(trans('date.event_not_found'));
            }
        }

        $success = false;
        if ($request->has('attendance')) {
            if (true === $event->binary_answer) {
                $available_states = \Config::get('enums.attendances_binary');
            } else {
                $available_states = \Config::get('enums.attendances');
            }
            if (in_array($request->get('attendance'), array_keys($available_states))) {
                $attendance = $event->current_user_attendance;
                if (null === $attendance) {
                    $success = $this->storeAttendance($event, \Auth::user(), ['attendance' => $request->get('attendance')]);
                } else {
                    var_dump($attendance);
                    $attendance->attendance = $available_states[$request->get('attendance')];
                    $success = $attendance->save();
                }
            }
        }


        // Check if changing the attendance worked.
        if (!$success) {
            $message = trans('date.change_own_attendance_error');
            if ($request->wantsJson()) {
                return \Response::json(['success' => false, 'message' => $message]);
            } else {
                return back()->withErrors($message);
            }
        }

        // If we arrive here everything went fine.
        $message = trans('date.own_attendance_saved');
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
     * @param Event $event
     * @param User $user
     * @param array $data
     * @return bool
     */
    abstract protected function storeAttendance($event, User $user, array $data);
}
