<?php

namespace App\Http\Controllers;

use App\Birthday;
use App\Gig;
use App\Rehearsal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
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

        if (Input::has('set') && array_key_exists(Input::get('set'), $this->sets)) {
            $sets = [Input::get('set') => $this->sets[Input::get('set')]];
        } else {
            $sets = $this->sets;
        }

        if (false !== $view = call_user_func_array([$this, $this->view_types[$view_type]], ['dates' => $this->getDates($sets)])) {
            return $view;
        } else {
            return redirect()->route('index')->withErrors(trans('date.view_type_not_found'));
        }
    }

    protected function calendarIndex (\Illuminate\Support\Collection $dates) {
        $calendar = Calendar::addEvents($dates);
        $calendar->setId('dates');

        return view('date.calendar', ['calendar' => $calendar]);
    }

    protected function listIndex (\Illuminate\Support\Collection $dates) {
        $dates = $dates->sortBy(function (Event $date) {
            return Carbon::now()->diffInSeconds($date->getStart(), false);
        });

        return view('date.list', ['dates' => $dates]);
    }

    private function getDates (array $sets) {
        $data = new Collection();

        foreach ($sets as $set) {
            $data->add(call_user_func_array([$set, 'all'], []));
        }

        return $data->flatten();
    }
}
