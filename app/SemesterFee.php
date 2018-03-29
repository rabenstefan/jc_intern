<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterFee extends Model
{
    protected $casts = [
        'paid' => 'boolean',
    ];

    public function semester() {
        return $this->belongsTo('App\Semester');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
