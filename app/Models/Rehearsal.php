<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;

/**
 * App\Models\Rehearsal
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property string|null $place
 * @property int $voice_id
 * @property int $semester_id
 * @property bool $binary_answer
 * @property bool $mandatory
 * @property float $weight
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RehearsalAttendance[] $rehearsal_attendances
 * @property-read \App\Models\Semester $semester
 * @property-read \App\Models\Voice $voice
 * @method static Builder|Rehearsal whereBinaryAnswer($value)
 * @method static Builder|Rehearsal whereCreatedAt($value)
 * @method static Builder|Rehearsal whereDescription($value)
 * @method static Builder|Rehearsal whereEnd($value)
 * @method static Builder|Rehearsal whereId($value)
 * @method static Builder|Rehearsal whereMandatory($value)
 * @method static Builder|Rehearsal wherePlace($value)
 * @method static Builder|Rehearsal whereSemesterId($value)
 * @method static Builder|Rehearsal whereStart($value)
 * @method static Builder|Rehearsal whereTitle($value)
 * @method static Builder|Rehearsal whereUpdatedAt($value)
 * @method static Builder|Rehearsal whereVoiceId($value)
 * @method static Builder|Rehearsal whereWeight($value)
 * @mixin \Eloquent
 */
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
