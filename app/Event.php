<?php

namespace App;

trait Event {
    use Date;

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
     * If the answer is binary, gives boolean, else the attendance string.
     *
     * @param $attendance
     * @return String|boolean
     */
    public function isAttendingEvent($attendance) {
        if ($this->hasBinaryAnswer()) {
            if (null === $attendance) return false;
            return \Config::get('enums.attendances_reversed')[$attendance->attendance] == 'yes';
        } else {
            if (null === $attendance) return \Config::get('enums.attendances_reversed')[0];
            return \Config::get('enums.attendances_reversed')[$attendance->attendance];
        }
    }

    public function hasAnsweredEvent($attendance) {
        if (null === $attendance) {
            return false;
        } else if ($this->hasBinaryAnswer()) {
            return \Config::get('enums.attendances')['maybe'] !== $attendance->attendance;
        } else {
            return true;
        }
    }

    public function needsAnswer() {
        return true;
    }
}
