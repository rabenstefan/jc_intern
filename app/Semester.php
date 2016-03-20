<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
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
}
