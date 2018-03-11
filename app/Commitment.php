<?php

namespace App;

class Commitment extends \Eloquent
{
    public function user() {
        return $this->hasOne('App\User');
    }

    public function gig() {
        return $this->hasOne('App\Gig');
    }
}
