<?php

namespace App\Http\Controllers;

use App\Birthday;
use App\Gig;
use App\Rehearsal;
use Illuminate\Http\Request;

use App\Http\Requests;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class DateController extends Controller {

    private $view_types = [
        'calendar' => 'calendarIndex',
        'list'     => 'listIndex',
    ];

    /**
     * Display a listing of the resource.
     *
     * @param String $view_type
     * @return \Illuminate\Http\Response
     */
    public function index($view_type = 'calendar') {
        // If we have no valid parameter we take the default.
        if (!array_key_exists($view_type, $this->view_types)) {
            $view_type = $this->view_types[0];
        }

        if (false !== $view = call_user_func_array([$this, $this->view_types[$view_type]], [])) {
            return $view;
        } else {
            return redirect()->route('index')->withErrors(trans('date.view_type_not_found'));
        }
    }

    protected function calendarIndex () {
        $rehearsals = Rehearsal::all();
        $gigs       = Gig::all();
        $birthdays  = Birthday::all();
        $calendar   = Calendar::addEvents($rehearsals)->addEvents($gigs)->addEvents($birthdays);

        $calendar->setId('dates');

        return view('date.calendar', ['calendar' => $calendar]);
    }

    protected function listIndex () {
        return view('date.list');
    }
}
