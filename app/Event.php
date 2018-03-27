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
        $this->setDateApplicableFilters();

        if (true === $this->needsAnswer()) {
            if (true === $this->hasAnswered(\Auth::user())) {
                switch ($this->isAttending(\Auth::user())) {
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
     * Gives the attendance string.
     *
     * @param $attendance
     * @return String
     */
    public function isAttendingEvent($attendance) {
        if (null === $attendance) return \Config::get('enums.attendances_reversed')[0];
        return \Config::get('enums.attendances_reversed')[$attendance->attendance];
    }

    /**
     * True, if a user has answered the event.
     *
     * @param $attendance
     * @return bool
     */
    public function hasAnsweredEvent($attendance) {
        if (null === $attendance) {
            return false;
        } else if ($this->hasBinaryAnswer()) {
            return \Config::get('enums.attendances')['maybe'] !== $attendance->attendance;
        } else {
            return true;
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
