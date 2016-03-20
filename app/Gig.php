<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    public function commitments() {
        return $this->belongsToMany('App\Commitment');
    }

    public function semester() {
        return $this->hasOne('App\Semester');
    }
}
