<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;

/**
 * App\Models\Gig
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property string $place
 * @property bool $binary_answer
 * @property int $semester_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GigAttendance[] $gig_attendances
 * @property-read \App\Models\Semester $semester
 * @method static Builder|Gig whereBinaryAnswer($value)
 * @method static Builder|Gig whereCreatedAt($value)
 * @method static Builder|Gig whereDescription($value)
 * @method static Builder|Gig whereEnd($value)
 * @method static Builder|Gig whereId($value)
 * @method static Builder|Gig wherePlace($value)
 * @method static Builder|Gig whereSemesterId($value)
 * @method static Builder|Gig whereStart($value)
 * @method static Builder|Gig whereTitle($value)
 * @method static Builder|Gig whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Gig extends \Eloquent implements IdentifiableEvent {
    use Event;

    protected $dates = ['start', 'end'];

    protected $calendar_options = [
        'className' => 'event-gig',
        'url' => ''
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

    /**
     * Instead of a constructor, we do it like dis, bitch.
     *
     * @param array $attributes
     * @param null $connection
     * @return Gig|\Eloquent
     */
    // TODO: Check and comment
    public function newFromBuilder($attributes = [], $connection = null) {
        $model = parent::newFromBuilder($attributes, $connection);
        $model->setApplicableFilters();
        return $model;
    }

    public function gig_attendances() {
        return $this->hasMany('App\Models\GigAttendance');
    }

    public function semester() {
        return $this->belongsTo('App\Models\Semester');
    }

    public function getShortName() {
        return 'gig';
    }

    public function getShortNamePlural() {
        return 'gigs';
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
        $this->calendar_options['url'] = route('gigs.show', ['gig' => $this->id]);

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

        $attendance = GigAttendance::where('user_id', $user->id)->where('gig_id', $this->id)->first();

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

        $attendance = GigAttendance::where('user_id', $user->id)->where('gig_id', $this->id)->first();

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

        $attendance = GigAttendance::where('user_id', $user->id)->where('gig_id', $this->id)->first();

        return $this->hasCommentedEvent($attendance);
    }

    public function getComment(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        if (null === $user) {
            return false;
        }

        $attendance = GigAttendance::where('user_id', $user->id)->where('gig_id', $this->id)->first();

        return $this->getCommentEvent($attendance);
    }
}
