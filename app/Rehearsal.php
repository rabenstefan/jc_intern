<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rehearsal extends Model
{
    protected $casts = [
        'mandatory' => 'boolean'
    ];

    public function semester() {
        return $this->hasOne('App\Semester');
    }

    public function voice() {
        return $this->hasOne('App\Voice');
    }

    public function attendance() {
        return $this->belongsToMany('App\Attendance');
    }
}
