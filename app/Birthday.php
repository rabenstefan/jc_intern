<?php

namespace App;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use MaddHatter\LaravelFullcalendar\Event;

class Birthday implements Event {
    protected $calendar_options = [
        'className' => 'event-birthday',
        'shortName' => 'birthday'
    ];

    private $title;
    private $start;
    private $end;

    public $description = '';
    public $needs_answer = false;

    public function __construct(User $user = null) {
        $this->title = trans('form.birthday') . " " . $user->first_name . ' ' . $user->last_name;

        // Date arithmetic: Set to current year, add one year if date is more than one week ago.
        $dateCurrentYear = $user->birthday;
        $dateCurrentYear->year = date('Y');
        if ($dateCurrentYear->lt(Carbon::now()->subWeek(1))) {
            $dateCurrentYear->addYear();
        }

        $this->start = $dateCurrentYear;
        $this->end   = $dateCurrentYear;
    }

    public function getShortName() {
        return 'birthday';
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay() {
        return true;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * Check if this date has a place
     *
     * @return Boolean
     */
    public function hasPlace() {
        return false;
    }

    /**
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions() {
        return $this->calendar_options;
    }

    /**
     * Override the all function.
     *
     * @return Collection
     */
    public static function all() {
        $collection = new Collection();
        $users = User::all();

        foreach ($users as $user) {
            if (isset($user->birthday) && $user->birthday instanceof Carbon) {
                $collection->add(new Birthday($user));
            }
        }

        return $collection;
    }
}
