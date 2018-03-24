<?php

namespace App;

trait Event {
    use Date;
    private $binary_answer;

    public function hasBinaryAnswer() {
        return $this->binary_answer;
    }

    public function isAttendingEvent(?Attendance $attendance) {
        if (null === $attendance) return \Config::get('enums.attendances_reversed')[0];
        return \Config::get('enums.attendances_reversed')[$attendance->attendance];
    }

    public function hasAnsweredEvent(?Attendance $attendance) {
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
