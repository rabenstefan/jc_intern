<?php

namespace App;

trait Attendance {
    public function event() {
        return null;
    }

    public function getPossibleAnswers() {
        if ($this->event()->hasBinaryAnswer()) {
            return \Config::get('enums.attendances_binary_reversed');
        }
        return \Config::get('enums.attendances_reversed');
    }
}
