<?php

namespace App;

use DateTime;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Carbon\Carbon;

class Rehearsal extends \Eloquent implements IdentifiableEvent {
    protected $dates = ['start', 'end'];

    protected $calendar_options = [
        'className' => 'event-rehearsal',
        'url' => '',
    ];

    protected $casts = [
        'mandatory' => 'boolean'
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
        'mandatory',
        'weight',
        'semester_id',
        'voice_id',
    ];

    public function semester() {
        return $this->hasOne('App\Semester');
    }

    public function voice() {
        return $this->hasOne('App\Voice');
    }

    public function attendances() {
        return $this->belongsToMany('App\Attendance');
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
     * Check if this date has a place
     *
     * @return Boolean
     */
    public function hasPlace() {
        return isset($this->place);
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
        $this->calendar_options['url'] = route('rehearsal.show', ['rehearsal' => $this->id]);
        
        return $this->calendar_options;
    }

    public function isAttending(User $user) {
        $attendance = Attendance::where('user_id', $user->id)->where('rehearsal_id', $this->id)->first();

        if (null === $attendance) return true;
        return !$attendance->excused;
    }

    /**
     * No need for old events.
     *
     * @param array $columns
     * @param bool $with_old
     * @return Rehearsal|\Eloquent[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function all($columns = ['*'], $with_old = false) {
        if ($with_old) {
            return parent::all($columns);
        } else {
            return parent::where('semester_id', '>=', Semester::current()->id)->get($columns);
        }
    }

    /**
     * Get the next rehersal after now()
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static The next rehearsal
     */
    public static function getNextRehearsal() {
        return Rehearsal::where('start', '>=', Carbon::now())->first();
    }
}
