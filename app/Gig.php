<?php

namespace App;

use DateTime;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;

class Gig extends \Eloquent implements IdentifiableEvent {
    protected $dates = ['start', 'end'];

    protected $calendar_options = [
        'className' => 'event-gig',
        'url' => '',
    ];

    public function commitments() {
        return $this->belongsToMany('App\Commitment');
    }

    public function semester() {
        return $this->hasOne('App\Semester');
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
        return false;
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
     * Get the event's ID
     *
     * @return int|string|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions() {
        $this->calendar_options['url'] = route('gig.show', ['gig' => $this->id]);

        return $this->calendar_options;
    }

    /**
     * No need for old events.
     *
     * @param array $columns
     * @param bool $with_old
     * @return static
     */
    public static function all($columns = ['*'], $with_old = false) {
        if ($with_old) {
            return parent::all($columns);
        } else {
            return parent::where('semester_id', '>=', Semester::current()->id)->get($columns);
        }
    }
}
