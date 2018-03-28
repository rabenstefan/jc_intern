<?php

namespace App;

use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Carbon\Carbon;

class Gig extends \Eloquent implements IdentifiableEvent {
    use Event;

    protected $dates = ['start', 'end'];

    protected $calendar_options = [
        'className' => 'event-gig',
        'url' => '',
        'shortName' => 'gig'
    ];

    protected $casts = [
        'binary_answer' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'place',
        'semester_id',
        'binary_answer',
    ];

    public function gig_attendances() {
        return $this->hasMany('App\GigAttendance');
    }

    public function semester() {
        return $this->belongsTo('App\Semester');
    }

    public function getShortName() {
        return 'gig';
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
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions() {
        $this->calendar_options['url'] = route('gig.show', ['gig' => $this->id]);

        return $this->calendar_options;
    }

    public function isAttending(User $user) {
        $attendance = GigAttendance::where('user_id', $user->id)->where('gig_id', $this->id)->first();

        return $this->isAttendingEvent($attendance);
    }

    public function hasAnswered(User $user) {
        $attendance = GigAttendance::where('user_id', $user->id)->where('gig_id', $this->id)->first();

        return $this->hasAnsweredEvent($attendance);
    }

}
