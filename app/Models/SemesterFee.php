<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterFee extends Model
{
    protected $casts = [
        'paid' => 'boolean',
    ];

    public function semester() {
        return $this->belongsTo('App\Models\Semester');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
