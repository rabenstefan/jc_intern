<?php

namespace App;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use MaddHatter\LaravelFullcalendar\Event;

class Birthday implements Event {
    protected $calendar_options = [
        'className' => 'event-birthday',
        'url' => '',
    ];

    private $title;
    private $start;
    private $end;

    public function __construct(User $user = null) {
        $this->title = trans('form.birthday') . "\n" . $user->first_name . ' ' . $user->last_name;

        $dateCurrentYear = $user->birthday;
        $dateCurrentYear->year = date('Y');
        $this->start = $dateCurrentYear;
        $this->end   = $dateCurrentYear;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title . (isset($this->place) ? "\n" . $this->place : '');
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
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions() {
        return $this->calendar_options;
    }

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
