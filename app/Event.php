<?php

namespace App;

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
    public abstract function isAttending(User $user = null);

    /**
     * Gives the attendance string.
     *
     * @param $attendance
     * @return String
     */
    protected function isAttendingEvent($attendance) {
        if (null === $attendance) return '';
        return \Config::get('enums.attendances_reversed')[$attendance->attendance];
    }

    /**
     * Returns true, if a user (or on null the authenticated user) has answered this Date.
     *
     * @param User|null $user
     * @return bool
     */
    public abstract function hasAnswered(User $user = null);

    /**
     * True, if a user has answered the event.
     *
     * @param $attendance
     * @return bool
     */
    protected function hasAnsweredEvent($attendance) {
        if (null === $attendance) {
            return false;
        } else if ($this->hasBinaryAnswer()) {
            return \Config::get('enums.attendances')['maybe'] !== $attendance->attendance;
        } else {
            return true;
        }
    }

    public abstract function hasCommented(User $user = null);
    public abstract function getComment(User $user = null);

    /**
     * True, if a user has comment on the event.
     *
     * @param $attendance
     * @return bool
     */
    protected function hasCommentedEvent($attendance) {
        if (null === $attendance) {
            return false;
        } else {
            return (null !== $attendance->comment && '' !== $attendance->comment);
        }
    }

    protected function getCommentEvent($attendance) {
        if (null === $attendance) {
            return '';
        } else {
            return $attendance->comment;
        }
    }

    /**
     * All Events need an answer of the user.
     *
     * @return bool
     */
    public function needsAnswer() {
        return true;
    }
}
