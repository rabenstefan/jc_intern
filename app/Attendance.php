<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends \Eloquent
{
    protected $casts = [
        'excused' => 'boolean',
        'missed'  => 'boolean',
    ];

    public function user() {
        return $this->hasOne('App\User');
    }

    public function rehearsal() {
        return $this->hasOne('App\Rehearsal');
    }
}
