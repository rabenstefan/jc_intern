<?php

namespace App;

use DateTime;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Carbon\Carbon;

class Gig extends \Eloquent implements IdentifiableEvent {
    protected $dates = ['start', 'end'];

    public $needs_answer = true; // Sould always be true

    protected $calendar_options = [
        'className' => 'event-gig',
        'url' => '',
        'shortName' => 'gig'
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
    ];

    public function commitments() {
        return $this->belongsToMany('App\Commitment');
    }

    public function semester() {
        return $this->hasOne('App\Semester');
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
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay() {
        //TODO: Add all day logic
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
        $this->calendar_options['url'] = route('gig.show', ['gig' => $this->id]);

        return $this->calendar_options;
    }

    public function isAttending(User $user) {
        $attendance = Commitment::where('user_id', $user->id)->where('gig_id', $this->id)->first();

        if (null === $attendance) return 'no';
        $attendances = \Config::get('enums.attendances');
        return array_flip($attendances)[$attendance->attendance];
    }

    public function hasAnswered(User $user, bool $maybe_is_not_an_answer = true) {
        $attendance = Commitment::where('user_id', $user->id)->where('gig_id', $this->id)->first();

        if (null === $attendance) {
            return false;
        } else if ($maybe_is_not_an_answer) {
            $attendances = \Config::get('enums.attendances');
            return $attendance->attendace !== $attendances['maybe'];
        } else {
            return true;
        }
    }

    /**
     * No need for old events.
     *
     * @param array $columns
     * @param bool $with_old
     * @return Gig|\Eloquent[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function all($columns = ['*'], $with_old = false) {
        if ($with_old) {
            return parent::all($columns);
        } else {
            return parent::where('end', '>=', Carbon::today())->get($columns);
        }
    }

    /**
     * Get the next gig after now().
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static The next Gig
     */
    public static function getNextGig() {
        return Gig::where('start', '>=', Carbon::now())->first();
    }
}
