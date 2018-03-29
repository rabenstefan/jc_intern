<?php

namespace App\Models;

class GigAttendance extends \Eloquent {
    use Attendance;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gig_id',
        'user_id',
        'attendance',
        'comment',
        'internal_comment',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function gig() {
        return $this->belongsTo('App\Models\Gig');
    }

    public function event() {
        return $this->gig();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Integer $gigId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForGig ($query, $gigId) {
        return $query->where('gig_id', $gigId);
    }
}
