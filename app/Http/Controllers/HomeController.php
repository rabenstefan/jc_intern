<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use App\Models\Rehearsal;
use App\Models\Gig;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
    }


    /**
     * Prepares a query to an event by already adjoining attendances of $user. This way, we can filter by attendance more easily.
     * Note that $limit is not applied until ->get() is ran on the return value of this function.
     *
     * @param $event_class
     * @param String $table_name
     * @param Carbon $reference_date
     * @param $user
     * @param int $limit
     * @return mixed
     */
    private function eventQueryBuild($event_class, String $table_name, Carbon $reference_date, $user, $limit = 3) {
        $singular = str_singular($table_name);

        $upcoming = $event_class::where('end', '>', $reference_date)->orderBy('start')->limit($limit);

        //TODO: Nicer query building.
        $upcoming = $upcoming->leftJoin($singular . '_attendances', function($leftJoin) use ($user, $table_name, $singular) {
            $leftJoin->on($table_name . '.id', '=', $singular . '_attendances.' . $singular . '_id');
            $leftJoin->where($singular . '_attendances.user_id', '=',  (int) $user->id );
        });

        return $upcoming;
    }

    /**
     * Take a pre-prepared query and modify it to hide one attendance type.
     *
     * @param $query
     * @param String $table_name
     * @param int $attendance
     * @param bool $hide_if_null
     * @return mixed
     */
    private function eventQueryHideByAttendance($query, String $table_name, int $attendance, bool $hide_if_null = false) {
        $singular = str_singular($table_name);

        //TODO: Nicer query building.
        return $query->where(function ($q) use ($singular, $attendance, $hide_if_null) {
            $q->where($singular . '_attendances.attendance', '!=', $attendance);
            if (false === $hide_if_null) { // Whenever NULL appears in an SQL-comparison, it will result in false. E.g. 'NULL = NULL' is false and 'NULL != 0' is false
                $q->orWhereNull($singular . '_attendances.attendance');
            }
        });
    }

    /**
     * Take a pre-prepared query and modify it to only show the given attendance types. null can be in the array.
     *
     * @param $query
     * @param String $table_name
     * @param array $attendances
     * @return mixed
     */
    private function eventQueryShowByAttendance($query, String $table_name, array $attendances) {
        $singular = str_singular($table_name);

        //TODO: Nicer query building.
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

    /**
     * Collect all information necessary to display the unanswered-panel
     *
     * @param array $event_types an array of ['class' => {class}, 'table_name' => string]-arrays, where {class} is a subclass of Event
     * @param Carbon $reference_date
     * @param $user
     * @return array
     */
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

    /**
     * Collect all information necessary for the missed_rehearsals-panel
     *
     * @param User $user
     * @return array
     */
    private function prepareMissedRehearsalsPanel(User $user) {
        $count = ['total' => $user->missedRehearsalsCount(), 'unexcused' => $user->missedRehearsalsCount(true)];
        $count['excused'] = $count['total'] - $count['unexcused'];

        $state = 'info';
        $data = ['over_limit' => false];

        // TODO: Don't make this hardcoded
        if (0 === $count['total']) {
            $state = 'success';
        } else if ($count['total'] > 5 or $count['unexcused'] > 2) {
            $state = 'warning';
            $data['over_limit'] = true;
        }

        return ['state' => $state, 'count' => $count, 'data' => $data];
    }

    /**
     * Collect all information necessary to display one of the next_{event}-panels.
     *
     * @param $event_class a subclass of Event
     * @param String $table_name the name of the table in the database
     * @param Carbon $reference_date
     * @param $user
     * @param bool $hide_not_attending
     * @param int $limit
     * @return array
     */
    private function prepareNextEventsPanel($event_class, String $table_name, Carbon $reference_date, $user, bool $hide_not_attending = true, $limit = 3) {
        $upcoming = $this->eventQueryBuild($event_class, $table_name, $reference_date, $user, $limit);

        if (true === $hide_not_attending) {
            $upcoming = $this->eventQueryHideByAttendance($upcoming, $table_name, \Config::get('enums.attendances')['no'], false);
        }

        $data = $upcoming->get();
        return ['state' => 'info', 'count' => $data->count(), 'data' => $data];
    }

    /**
     * Collect all information necessary to display upcoming birthdays
     *
     * @param Carbon $reference_date
     * @return array
     */
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
