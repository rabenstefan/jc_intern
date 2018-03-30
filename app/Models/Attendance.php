<?php

namespace App\Models;

/**
 * Trait Attendance
 * @package App\Models
 *
 * @property boolean attendance
 * @property string comment
 * @property string comment_internal
 */
trait Attendance {
    /**
     * @return Event
     */
    abstract public function event();

    /**
     * @return array
     */
    public function getPossibleAnswers() {
        if ($this->event()->hasBinaryAnswer()) {
            return \Config::get('enums.attendances_binary_reversed');
        }
        return \Config::get('enums.attendances_reversed');
    }
}
