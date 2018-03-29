<?php

namespace App\Models;

use MaddHatter\LaravelFullcalendar\IdentifiableEvent;

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

    public function newFromBuilder($attributes = [], $connection = null) {
        $model = parent::newFromBuilder($attributes, $connection);
        $model->setApplicableFilters();
        return $model;
    }

    public function semester() {
        return $this->belongsTo('App\Models\Semester');
    }

    public function voice() {
        return $this->belongsTo('App\Models\Voice');
    }

    public function rehearsal_attendances() {
        return $this->hasMany('App\Models\RehearsalAttendance');
    }

    public function getShortName() {
        return 'rehearsal';
    }

    public function getShortNamePlural() {
        return 'rehearsals';
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions() {
        $this->calendar_options['url'] = route('rehearsals.show', ['rehearsal' => $this->id]);
        
        return $this->calendar_options;
    }

    /**
     * Returns answer, if a user (or on null the authenticated user) has answered this Date.
     *
     * @param User|null $user
     * @return bool
     */
    public function isAttending(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        $attendance = RehearsalAttendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        return $this->isAttendingEvent($attendance);
    }

    /**
     * Returns true, if a user (or on null the authenticated user) has answered this Date.
     *
     * @param User|null $user
     * @return bool
     */
    public function hasAnswered(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        if (null === $user) { // Needed for seeding
            return false;
        }

        $attendance = RehearsalAttendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        return $this->hasAnsweredEvent($attendance);
    }

    //TODO: Comment
    public function hasCommented(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        if (null === $user) {
            return false;
        }

        $attendance = RehearsalAttendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        return $this->hasCommentedEvent($attendance);
    }

    public function getComment(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        if (null === $user) {
            return false;
        }

        $attendance = RehearsalAttendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        return $this->getCommentEvent($attendance);
    }
}
