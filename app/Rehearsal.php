<?php

namespace App;

use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Carbon\Carbon;

class Rehearsal extends \Eloquent implements IdentifiableEvent {
    use Event;

    protected $dates = ['start', 'end'];

    protected $calendar_options = [
        'className' => 'event-rehearsal',
        'url' => '',
        'shortName' => 'rehearsal'
    ];

    protected $casts = [
        'mandatory' => 'boolean',
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
        'binary_answer',
        'mandatory',
        'weight',
        'semester_id',
        'voice_id',
    ];

    public function semester() {
        return $this->belongsTo('App\Semester');
    }

    public function voice() {
        return $this->belongsTo('App\Voice');
    }

    public function rehearsal_attendances() {
        return $this->hasMany('App\RehearsalAttendance');
    }

    public function getShortName() {
        return 'rehearsal';
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
        $this->calendar_options['url'] = route('rehearsal.show', ['rehearsal' => $this->id]);
        
        return $this->calendar_options;
    }

    public function isAttending(User $user) {
        $attendance = RehearsalAttendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        return $this->isAttendingEvent($attendance);
    }

    public function hasAnswered(User $user) {
        $attendance = RehearsalAttendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        return $this->hasAnsweredEvent($attendance);
    }
}
