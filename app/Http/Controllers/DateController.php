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
use Symfony\Component\Debug\Exception\FatalThrowableError;

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

        $filters = null;
        if (Input::has('showOnly') && is_array(Input::get('showOnly')) && (count(Input::get('showOnly')) > 0 )) {
            $filters = Input::get('showOnly');
        }

        $with_old = 'calendar' === $view_type;

        if (false !== $view = call_user_func_array([$this, $this->view_types[$view_type]], ['dates' => $this->getDates($this->sets, $with_old), 'override_filters' => $filters])) {
            return $view;
        } else {
            return redirect()->route('index', ['override_filters' => $filters])->withErrors(trans('date.view_type_not_found'));
        }
    }

    protected function calendarIndex (\Illuminate\Support\Collection $dates, $filters) {
        if (!(null === $filters || is_array($filters))) {
            throw new FatalThrowableError('Type of second parameter of calendarIndex has to be null or array');
        }
        $calendar = Calendar::addEvents($dates);
        $calendar->setId('dates');

        return view('date.calendar', ['calendar' => $calendar, 'override_filters' => $filters]);
    }

    protected function listIndex (\Illuminate\Support\Collection $dates, $filters) {
        if (!(null === $filters || is_array($filters))) {
            throw new FatalThrowableError('Type of second parameter of listIndex has to be null or array');
        }
        $dates = $dates->sortBy(function (Event $date) {
            return Carbon::now()->diffInSeconds($date->getStart(), false);
        });

        return view('date.list', ['dates' => $dates, 'override_filters' => $filters]);
    }

    private function getDates (array $sets, bool $with_old = false) {
        $data = new Collection();

        foreach ($sets as $set) {
            $data->add(call_user_func_array([$set, 'all'], [['*'], $with_old]));
        }

        return $data->flatten();
    }
}
