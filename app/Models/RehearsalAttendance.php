<?php

namespace App\Models;

class RehearsalAttendance extends \Eloquent {
    use Attendance;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rehearsal_id',
        'user_id',
        'attendance',
        'comment',
        'internal_comment',
        'missed',
    ];

    protected $casts = [
        'missed'  => 'boolean',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function rehearsal() {
        return $this->belongsTo('App\Models\Rehearsal');
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
