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
use Cache;

class DateController extends Controller {
    // There are multiple ways to display the dates.
    private static $view_types = [
        'list'     => 'listIndex',
        'calendar' => 'calendarIndex',
    ];

    // These are the types our dates can be in.
    private static $date_types = [
        'rehearsals' => Rehearsal::class,
        'gigs'       => Gig::class,
        'birthdays'  => Birthday::class,
    ];

    // Statuses that the UI can filter by.
    private static $date_statuses = [
        'going',
        'not-going',
        'maybe-going',
        'unanswered'
    ];

    /**
     * DateController constructor.
     */
    public function __construct() {
        $this->middleware('auth', ['except' => ['renderIcal']]);
    }

    /**
     * Get the supported types of displaying the dates.
     *
     * @return array
     */
    public static function getViewTypes() {
        return array_keys(self::$view_types);
    }

    /**
     * Returns available date types as an arrays of strings (str_singular was applied to)
     *
     * @return array
     */
    public static function getDateTypes() {
        return array_map('str_singular', array_keys(self::$date_types));
    }

    public static function getDateStatuses() {
        return self::$date_statuses;
    }

    /**
     * Compare the given date types to the available ones and return the inverse.
     * If a date type is unknown, it will be dropped.
     *
     * @param array $date_types
     * @return array
     */
    public static function invertDateTypes (array $date_types) {
        $available_types = self::getDateTypes();
        return array_diff($available_types, array_intersect($available_types, $date_types));
    }

    public static function invertDateStatuses (array $date_statuses) {
        return array_diff(self::$date_statuses, array_intersect(self::$date_statuses, $date_statuses));
    }

    /**
     * Display a listing of the resource.
     *
     * Either renders a list view (default) or a calendar view of the dates. Can be filtered by Input.
     *
     * @param String $view_type
     * @return \Illuminate\Http\Response
     */
    public function index ($view_type = 'list') {
        // If we have no valid parameter we take the default.
        if (!array_key_exists($view_type, self::$view_types)) {
            $view_type = self::$view_types[0];
        }

        $override_types = [];
        if (Input::has('hideByType') && is_array(Input::get('hideByType')) && (count(Input::get('hideByType')) > 0 )) {
            $override_types = Input::get('hideByType');
            $override_types = array_intersect(self::getDateTypes(), $override_types); // Because never trust the client!
        }

        $override_statuses = [];
        if (Input::has('hideByStatus') && is_array(Input::get('hideByStatus')) && (count(Input::get('hideByStatus')) > 0 )) {
            $override_statuses = Input::get('hideByStatus');
            $override_statuses = array_intersect(self::getDateStatuses(), $override_statuses); // Because never trust the client!
        }

        $with_old = 'calendar' === $view_type;

        $view = call_user_func_array(
            [
                $this,
                self::$view_types[$view_type]
            ],
            [
                'dates' => $this->getDates(self::$date_types, $with_old),
                'override_types' => $override_types,
                'override_statuses' => $override_statuses
            ]
        );

        return false !== $view ? $view : redirect()->route(
            'index',
            ['override_types' => $override_types, 'override_statuses' => $override_statuses]
        )->withErrors(trans('date.view_type_not_found'));
    }

    /**
     * Render the calender view of the given dates.
     *
     * @param $dates
     * @param array $override_types
     * @param array $override_statuses
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function calendarIndex ($dates, array $override_types = [], array $override_statuses = []) {
        $calendar = Calendar::addEvents($dates);
        $calendar->setId('dates');
        return view('date.calendar', ['calendar' => $calendar, 'override_types' => $override_types, 'override_statuses' => $override_statuses]);
    }

    /**
     * Render the list view of the given dates.
     *
     * @param \Illuminate\Support\Collection $dates
     * @param array $override_types
     * @param array $override_statuses
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function listIndex (\Illuminate\Support\Collection $dates, array $override_types = [], array $override_statuses = []) {
        $dates = $dates->sortBy(function (Event $date) {
            return Carbon::now()->diffInSeconds($date->getStart(), false);
        });

        return view('date.list', ['dates' => $dates, 'override_types' => $override_types, 'override_statuses' => $override_statuses]);
    }

    /**
     * @param array $date_types
     * @param bool $with_old
     * @return \Illuminate\Support\Collection
     */
    private function getDates (array $date_types, bool $with_old = false) {
        $data = new Collection();

        foreach ($date_types as $set) {
            $data->add(call_user_func_array([$set, 'all'], [['*'], $with_old]));
        }

        return $data->flatten();
    }


    public function calendarSync() {
        return view('date.calendar_sync', ['date_types' => array_keys(self::$date_types)]);
    }

    /**
     * Method to render and ICAL calender.
     *
     * @return mixed
     */
    public function renderIcal() {
        $date_types = [];
        if (Input::has('show_types') && is_array(Input::get('show_types')) && (count(Input::get('show_types')) > 0 )) {
            $show_types = Input::get('show_types');

            foreach (self::$date_types as $key => $value) {
                // For now, we just ignore unknown elements from the GET-array.
                if (in_array($key, $show_types)) {
                    $date_types[$key] = $value;
                }
            }
        }

        if (empty($date_types)) {
            $date_types = self::$date_types;
        }

        $calendar_id = implode('-', array_keys($date_types));

        // Only re-render every 2 hours to serve annoying clients slightly faster
        // Also, this makes it so the UIDs generated by Calendar don't change on every request
        $ical = Cache::get('render_Ical_'.$calendar_id);
        if (null === $ical) {
            $dates = $this->getDates($date_types);

            $vCalendar = new \Eluceo\iCal\Component\Calendar('jazzchor_'.$calendar_id);
            foreach ($dates as $date) {
                $vEvent = new \Eluceo\iCal\Component\Event();
                $vEvent
                    ->setDtStart($date->getStart())
                    ->setDtEnd($date->getEnd())
                    ->setNoTime($date->isAllDay())
                    ->setSummary($date->getTitle())
                    ->setDescription($date->description);
                if (true === $date->hasPlace()) {
                    $vEvent->setLocation($date->place);
                }
                $vCalendar->addComponent($vEvent);
            }

            $ical = $vCalendar->render();
            Cache::put('render_Ical_'.$calendar_id, $ical, 120);
        }

        return response($ical)->setExpires(Carbon::now('UTC')->addHours(12)) // make sync-clients wait for 12 hours
            ->withHeaders([
                'Content-Type' => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="calendar_'.$calendar_id.'.ics"'
            ]);
    }
}
