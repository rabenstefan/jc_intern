<?php

namespace App\Http\Controllers;

use App\Birthday;
use App\Event;
use App\Rehearsal;
use App\Gig;
use App\Semester;
use App\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    private function eventQueryBuild($event_class, String $table_name, Carbon $reference_date, $user, $count = 3) {
        $singular = str_singular($table_name);

        $upcoming = $event_class::where('end', '>', $reference_date)->orderBy('start')->limit($count);

        $upcoming = $upcoming->leftJoin($singular . '_attendances', function($leftJoin) use ($user, $table_name, $singular) {
            $leftJoin->on($table_name . '.id', '=', $singular . '_attendances.' . $singular . '_id');
            $leftJoin->where($singular . '_attendances.user_id', '=',  (int) $user->id );
        });

        return $upcoming;
    }

    private function eventQueryHideByAttendance($query, String $table_name, int $attendance, bool $hide_if_null = false) {
        $singular = str_singular($table_name);

        return $query->where(function ($q) use ($singular, $attendance, $hide_if_null) {
            $q->where($singular . '_attendances.attendance', '!=', $attendance);
            if (false === $hide_if_null) { // Whenever NULL appears in an SQL-comparison, it will result in false. E.g. 'NULL = NULL' is false and 'NULL != 0' is false
                $q->orWhereNull($singular . '_attendances.attendance');
            }
        });
    }

    private function eventQueryShowByAttendance($query, String $table_name, array $attendances) {
        $singular = str_singular($table_name);

        $query = $query->where (function($q) use ($singular, $attendances) {
            foreach ($attendances as $attendance) {
                if (null === $attendance) {
                    $q->orWhereNull($singular . '_attendances.attendance');
                } else {
                    $q->orWhere($singular . '_attendances.attendance', '=', \Config::get('enums.attendances')[$attendance]);
                }
            }
        });
        return $query;
    }

    private function prepareUnansweredPanel(array $event_types, Carbon $reference_date, $user) {
        $count = ['unanswered' => 0, 'maybe' => 0, 'total' => 0];
        foreach ($event_types as $event_type) {
            $query = $this->eventQueryBuild($event_type['class'], $event_type['table_name'], $reference_date, $user);
            $query = $this->eventQueryShowByAttendance($query, $event_type['table_name'], [null]);
            $count['unanswered'] += $query->count();

            $query = $this->eventQueryBuild($event_type['class'], $event_type['table_name'], $reference_date, $user);
            $query = $this->eventQueryShowByAttendance($query, $event_type['table_name'], ['maybe']);
            $count['maybe'] += $query->count();
        }
        $count['total'] = $count['maybe'] + $count['unanswered'];

        $state = 'success';
        if (0 !== $count['unanswered']) {
            $state = 'error';
        } else if (0 !== $count['maybe']) {
            $state = 'warning';
        }

        return ['state' => $state, 'count' => $count, 'data' => []];
    }

    private function prepareMissedRehearsalsPanel(User $user) {
        $count = ['total' => $user->missedRehearsalsCount(), 'unexcused' => $user->missedRehearsalsCount(true)];
        $count['excused'] = $count['total'] - $count['unexcused'];

        $state = 'info';
        if (0 === $count['total']) {
            $state = 'success';
        }
        //TODO: warn if too many missed rehearsals
        return ['state' => $state, 'count' => $count, 'data' => []];
    }

    private function prepareNextEventsPanel($event_class, String $table_name, Carbon $reference_date, $user, bool $hide_not_attending = true, $count = 3) {
        $upcoming = $this->eventQueryBuild($event_class, $table_name, $reference_date, $user, $count);

        if (true === $hide_not_attending) {
            $upcoming = $this->eventQueryHideByAttendance($upcoming, $table_name, \Config::get('enums.attendances')['no'], false);
        }

        $data = $upcoming->get();
        return ['state' => 'info', 'count' => $data->count(), 'data' => $data];
    }

    private function prepareNextBirthdaysPanel(Carbon $reference_date) {
        $upcoming_birthdays = Birthday::all();

        // Consider Birthdays 3 days in the past and 10 days in the future
        $lower_bound = $reference_date->copy()->subDays(3);
        $upper_bound = $reference_date->copy()->addDays(10);

        $upcoming_birthdays = $upcoming_birthdays->filter(function($value) use ($reference_date, $lower_bound, $upper_bound) {
            return $value->getStart()->between($lower_bound, $upper_bound);});

        $upcoming_birthdays = $upcoming_birthdays->sortBy(function($item) {return $item->getStart();});

        return ['state' => 'info', 'count' => $upcoming_birthdays->count(), 'data' => $upcoming_birthdays];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $today = Carbon::today();
        $now = Carbon::now();
        $user = \Auth::user();

        /*
         * Each {name}_panel consists of an array with the keys ['state', 'count', 'data'].
         */

        return view('home', [
            'unanswered_panel' => $this->prepareUnansweredPanel([['class' => Gig::class, 'table_name' => 'gigs'], ['class' => Rehearsal::class, 'table_name' => 'rehearsals']], $today, $user),
            'missed_rehearsals_panel' => $this->prepareMissedRehearsalsPanel($user),
            'next_rehearsals_panel' => $this->prepareNextEventsPanel(Rehearsal::class, 'rehearsals', $now, $user, false),
            'next_gigs_panel' => $this->prepareNextEventsPanel(Gig::class, 'gigs', $now, $user, true),
            'next_birthdays_panel' => $this->prepareNextBirthdaysPanel($today),
            'today' => $today,
            'now'   => $now,
            'user' => $user
        ]);
    }
}
