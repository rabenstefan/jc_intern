<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    public function users() {
        return $this->hasMany('App\User');
    }

    public function super_group() {
        return $this->hasOne('App\Voice', 'super_group');
    }

    public function rehearsals() {
        return $this->hasMany('App\Rehearsal');
    }
}
