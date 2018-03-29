<?php

namespace App\Models;

trait Attendance {
    abstract public function event();

    public function getPossibleAnswers() {
        if ($this->event()->hasBinaryAnswer()) {
            return \Config::get('enums.attendances_binary_reversed');
        }
        return \Config::get('enums.attendances_reversed');
    }
}
