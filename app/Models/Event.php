<?php

namespace App\Models;

/**
 * Trait Event
 * @package App\Models
 */
trait Event {
    use Date {
        Date::setApplicableFilters as private setDateApplicableFilters;
    }

    /**
     * Extend the Date's applicable filters by the attendance of the event.
     */
    protected function setApplicableFilters() {
        if ($this->needsAnswer()) {
            if ($this->hasAnswered()) {
                switch ($this->isAttending()) {
                    case 'yes':
                        $this->applicable_filters[] = 'going';
                        break;
                    case 'maybe':
                        $this->applicable_filters[] = 'maybe-going';
                        break;
                    case 'no':
                        $this->applicable_filters[] = 'not-going';
                        break;
                }
            } else {
                $this->applicable_filters[] = 'unanswered';
            }
        }
        $this->setDateApplicableFilters();
    }

    /**
     * Returns true if only 'yes' and 'no' are acceptable answers for this date.
     *
     * @return boolean
     */
    public function hasBinaryAnswer() {
        if (isset($this->binary_answer)) {
            return $this->binary_answer;
        }

        return true;
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

        $attendance = $this->getAttendance($user);

        if (null === $attendance || null === $attendance->attendance) return '';
        return \Config::get('enums.attendances_reversed')[$attendance->attendance];
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

        $attendance = $this->getAttendance($user);

        if (null === $attendance || null === $attendance->attendance) {
            return false;
        } else if ($this->hasBinaryAnswer()) {
            return \Config::get('enums.attendances')['maybe'] !== $attendance->attendance;
        } else {
            return true;
        }
    }

    /**
     * @param User $user
     * @return Attendance
     */
    abstract protected function getAttendance(User $user);

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    abstract protected function getAttendances();

    /**
     * Should return true, if a user has already commented on an attendance.
     *
     * @param User|null $user
     * @return boolean
     */
    public function hasCommented(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        $attendance = $this->getAttendance($user);

        // Make use of lazy evaluation (attendance will be set if second part is evaluated).
        return null !== $attendance && !empty($attendance->comment);
    }

    /**
     * Returns the comment a user has given on the Event.
     *
     * @param User|null $user
     * @return bool
     */
    public function getComment(User $user = null) {
        if (null === $user) {
            $user = \Auth::user();
        }

        $attendance = $this->getAttendance($user);

        return null === $attendance ? '' : $attendance->comment;
    }

    /**
     * All Events need an answer of the user.
     *
     * @return bool
     */
    public function needsAnswer() {
        return true;
    }

    /**
     * Get the number of people who will attend an event (filtered by voice if given)
     *
     * @param Voice|null $voice
     * @return int
     */
    public function getAttendanceCount(Voice $voice = null) {
        // TODO: this function fires too many sql-queries

        $attendances = $this->getAttendances()->filter(function ($key) {
            return $key->attendance === \Config::get('enums.attendances')['yes'];
        });

        if (null !== $voice) {
            // Get sub_voices as well.
            $voices = [$voice->id];
            foreach ($voice->children as $sub_voice) {
                $voices[] = $sub_voice->id;
            }
            $attendances = $attendances->load('user')->whereIn('user.voice_id', $voices);
        }

        return $attendances->count();
    }
}
