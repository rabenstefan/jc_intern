<?php

namespace App;

use Carbon\Carbon;

class Semester extends \Eloquent {
    public function gigs() {
        return $this->belongsToMany('App\Gig');
    }

    public function rehearsals() {
        return $this->belongsToMany('App\Rehearsal');
    }

    public function echoed() {
        return $this->belongsToMany('App\User', 'last_echo');
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
