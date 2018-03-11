<?php

namespace App;

class Attendance extends \Eloquent {
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

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Integer $rehearsalId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForRehearsal ($query, $rehearsalId) {
        return $query->where('rehearsal_id', $rehearsalId);
    }
}
