<?php

namespace App;

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
    ];

    public function user() {
        return $this->hasOne('App\User');
    }

    public function gig() {
        return $this->hasOne('App\Gig');
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
