<?php

namespace App;

class RehearsalAttendance extends \Eloquent {
    use Attendance;

    protected $casts = [
        'missed'  => 'boolean',
    ];

    public function user() {
        return $this->hasOne('App\User');
    }

    public function rehearsal() {
        return $this->hasOne('App\Rehearsal');
    }

    public function event() {
        return $this->rehearsal();
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
