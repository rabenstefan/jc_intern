<?php

namespace App;

trait Event {
    use Date;

    public function currentUserIsAttending() {
        return $this->isAttendingEvent($this->current_user_attendance);
    }

    public function currentUserHasAnswered() {
        return $this->hasAnsweredEvent($this->current_user_attendance);
    }

    public function isAttendingEvent($attendance = null) {
        if (null === $attendance) return \Config::get('enums.attendances_reversed')[0];
        if (class_uses($attendance, false)) {
            return \Config::get('enums.attendances_reversed')[$attendance->attendance];
        }
    }

    public function hasAnsweredEvent($attendance = null) {
        if (null === $attendance) {
            return false;
        } else if ($this->binary_answer) {
            return \Config::get('enums.attendances')['maybe'] !== $attendance->attendance;
        } else {
            return true;
        }
    }

    public function needsAnswer() {
        return true;
    }
}
