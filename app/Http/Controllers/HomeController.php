<?php

namespace App\Http\Controllers;

use App\Birthday;
use App\Event;
use App\Rehearsal;
use App\Gig;
use App\Semester;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    private function prepareUnansweredPanel(Carbon $reference_date, $user) {

    }

    private function prepareMissedRehearsalsPanel(Carbon $reference_date, $user) {

    }

    private function prepareNextEventsPanel($event_class, String $table_name, Carbon $reference_date, $user) {

        $singular = str_singular($table_name);

        $upcoming = $event_class::where('end', '>=', $reference_date)->orderBy('start')->limit(3);
        /*
         * This left join/where-abomination is designed to hide all gigs, that the user has rejected (not attending),
         * but show the ones that are either unanswered or in any other state than 'no'
         */

        $upcoming = $upcoming->leftJoin($singular . '_attendances', function($leftJoin) use ($user, $table_name, $singular) {
            $leftJoin->on($table_name . '.id', '=', $singular . '_attendances.' . $singular . '_id');
            $leftJoin->where($singular . '_attendances.user_id', '=',  (int) $user->id );
        });

        $upcoming = $upcoming->where(function($query) use ($singular) {
            // Whenever NULL appears in an SQL-comparison, it will result in false. E.g. 'NULL = NULL' is false
            $query->whereNull($singular . '_attendances.attendance')->orWhere($singular . '_attendances.attendance', '!=', \Config::get('enums.attendances')['no']);
        });

        return ['data' => $upcoming->get()];
    }

    private function prepareNextBirthdaysPanel(Carbon $reference_date) {
        $upcoming_birthdays = Birthday::all();

        // Consider Birthdays 3 days in the past and 10 days in the future
        $upcoming_birthdays = $upcoming_birthdays->filter(function($value) use ($reference_date) {
            return $value->getStart()->gte($reference_date->subDays(3)) && $value->getStart()->lte($reference_date->addDays(10));});


        $upcoming_birthdays = $upcoming_birthdays->sortBy(function($item) {return $item->getStart();});

        return ['count' => $upcoming_birthdays->count(), 'data' => $upcoming_birthdays];
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

        return view('home', [
            'unanswered' => $this->prepareUnansweredPanel($today, $user),
            'missed_rehearsals' => $this->prepareMissedRehearsalsPanel($today, $user),
            'next_rehearsals' => $this->prepareNextEventsPanel(Rehearsal::class, 'rehearsals', $now, $user),
            'next_gigs' => $this->prepareNextEventsPanel(Gig::class, 'gigs', $now, $user),
            'next_birthdays' => $this->prepareNextBirthdaysPanel($today),
            'today' => $today,
            'now'   => $now,
            'user' => $user
        ]);


        $next_rehearsal = Rehearsal::getNextRehearsal();
        if (null === $next_rehearsal) {
            //TODO: Move to lang.
            $rehearsal = ['diff' => 'nie', 'datetime' => 'keine Probe gefunden', 'location' => 'nirgendwo'];
        } else {
            $rehearsal = ['diff' => $next_rehearsal->start->diffForHumans(), 'datetime' => $next_rehearsal->start->formatLocalized('%c'), 'location' => $next_rehearsal->place];
        }

        $next_gig = Gig::getNextGig();
        if (null === $next_gig) {
            $gig = ['diff' => 'nie', 'datetime' => 'keine Probe gefunden', 'location' => 'nirgendwo'];
        } else {
            $gig = ['diff' => $next_gig->start->diffForHumans(), 'datetime' => $next_gig->start->toDayDateTimeString(), 'location' => $next_gig->place];
        }



        // Show "add semester" if current semester is (almost) over and it's the last one.
        $semester_warning = (new Carbon(Semester::current()->end))->diffInMonths(Carbon::today()) <= 1;

        return view('home', [
            'next_rehearsal' => $rehearsal,
            'next_gig' => $gig,
            'upcoming_birthdays' => $upcoming_birthdays,
            'semester_warning' => $semester_warning
        ]);
    }
}
