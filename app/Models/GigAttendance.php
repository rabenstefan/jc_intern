<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\GigAttendance
 *
 * @property int $id
 * @property int $user_id
 * @property int $gig_id
 * @property int $attendance
 * @property string|null $comment
 * @property string|null $internal_comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Gig $gig
 * @property-read \App\Models\User $user
 * @method static Builder|GigAttendance forGig($gigId)
 * @method static Builder|GigAttendance whereAttendance($value)
 * @method static Builder|GigAttendance whereComment($value)
 * @method static Builder|GigAttendance whereCreatedAt($value)
 * @method static Builder|GigAttendance whereGigId($value)
 * @method static Builder|GigAttendance whereId($value)
 * @method static Builder|GigAttendance whereInternalComment($value)
 * @method static Builder|GigAttendance whereUpdatedAt($value)
 * @method static Builder|GigAttendance whereUserId($value)
 * @mixin \Eloquent
 */
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
     * @param Builder $query
     * @param Integer $gigId
     * @return Builder
     */
    public function scopeForGig ($query, $gigId) {
        return $query->where('gig_id', $gigId);
    }
}
