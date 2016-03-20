<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
    public function user() {
        return $this->hasOne('App\User');
    }

    public function gig() {
        return $this->hasOne('App\Gig');
    }
}
