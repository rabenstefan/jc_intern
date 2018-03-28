<?php

namespace App;

use Carbon\Carbon;

class Semester extends \Eloquent {
    public function gigs() {
        return $this->hasMany('App\Gig');
    }

    public function rehearsals() {
        return $this->hasMany('App\Rehearsal');
    }

    public function echoed() {
        return $this->hasMany('App\User', 'last_echo');
    }

    public function semester_fees() {
        return $this->hasMany('App\SemesterFee');
    }

    public static function current() {
        $today = Carbon::today();
        return Semester::where('start', '<=', $today)->where('end', '>=', $today)->firstOrFail();
    }

    public static function last() {
        return Semester::orderBy('end', 'desc')->firstOrFail();
    }
}
