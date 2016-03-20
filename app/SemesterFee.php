<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterFee extends Model
{
    protected $casts = [
        'paid' => 'boolean',
    ];

    public function semester() {
        return $this->hasOne('App\Semester');
    }

    public function user() {
        return $this->hasOne('App\User');
    }
}
