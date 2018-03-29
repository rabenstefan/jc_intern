<?php

namespace App\Models;

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
