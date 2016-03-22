<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    protected $casts = [
        'child_group' => 'boolean'
    ];

    public function users() {
        return $this->hasMany('App\User');
    }

    public function super_group() {
        return $this->hasOne('App\Voice', 'super_group');
    }

    public function rehearsals() {
        return $this->hasMany('App\Rehearsal');
    }

    public static function getChildVoices() {
        return Voice::all()->where('child_group', true);
    }
}
