<?php

namespace App\Http\Controllers;

use App\Birthday;
use App\Gig;
use App\Rehearsal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;
use MaddHatter\LaravelFullcalendar\Event;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class DateController extends Controller {
    private $view_types = [
        'calendar' => 'calendarIndex',
        'list'     => 'listIndex',
    ];

    private $sets = [
        'rehearsals' => Rehearsal::class,
        'gigs'       => Gig::class,
        'birthdays'  => Birthday::class,
    ];

    /**
     * DateController constructor.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param String $view_type
     * @return \Illuminate\Http\Response
     */
    public function index ($view_type = 'list') {
        // If we have no valid parameter we take the default.
        if (!array_key_exists($view_type, $this->view_types)) {
            $view_type = $this->view_types[0];
        }

        $sets = new Collection();
        $sets_keys = new Collection();
        // Check for valid input. Should be an array of "set"s, referencing keys in $this->sets
        if (Input::has('sets') && is_array(Input::get('sets'))) {
            foreach (Input::get('sets') as $set_input) {
                if (array_key_exists($set_input, $this->sets)) {
                    $sets->add($this->sets[$set_input]);
                    $sets_keys->add($set_input);
                }
            }
        }

        // If we have no valid parameter we take the default.
        if ($sets->isEmpty()) {
            $sets = $this->sets;
            $sets_keys = array_keys($this->sets);
        } else {
            $sets = $sets->toArray();
            $sets_keys = $sets_keys->toArray();
        }

        $with_old = 'calendar' === $view_type;

        if (false !== $view = call_user_func_array([$this, $this->view_types[$view_type]], ['dates' => $this->getDates($sets, $with_old), 'current_sets' => $sets_keys])) {
            return $view;
        } else {
            return redirect()->route('index', ['current_sets' => $sets_keys])->withErrors(trans('date.view_type_not_found'));
        }
    }

    protected function calendarIndex (\Illuminate\Support\Collection $dates, array $sets_keys) {
        $calendar = Calendar::addEvents($dates);
        $calendar->setId('dates');

        return view('date.calendar', ['calendar' => $calendar, 'current_sets' => $sets_keys]);
    }

    protected function listIndex (\Illuminate\Support\Collection $dates, array $sets_keys) {
        $dates = $dates->sortBy(function (Event $date) {
            return Carbon::now()->diffInSeconds($date->getStart(), false);
        });

        return view('date.list', ['dates' => $dates, 'current_sets' => $sets_keys]);
    }

    private function getDates (array $sets, bool $with_old = false) {
        $data = new Collection();

        foreach ($sets as $set) {
            $data->add(call_user_func_array([$set, 'all'], [['*'], $with_old]));
        }

        return $data->flatten();
    }
}
