<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\RehearsalAttendance
 *
 * @property int $id
 * @property int $user_id
 * @property int $rehearsal_id
 * @property int $attendance
 * @property string|null $comment
 * @property string|null $internal_comment
 * @property bool $missed
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Rehearsal $rehearsal
 * @property-read \App\Models\User $user
 * @method static Builder|RehearsalAttendance forRehearsal($rehearsalId)
 * @method static Builder|RehearsalAttendance whereAttendance($value)
 * @method static Builder|RehearsalAttendance whereComment($value)
 * @method static Builder|RehearsalAttendance whereCreatedAt($value)
 * @method static Builder|RehearsalAttendance whereId($value)
 * @method static Builder|RehearsalAttendance whereInternalComment($value)
 * @method static Builder|RehearsalAttendance whereMissed($value)
 * @method static Builder|RehearsalAttendance whereRehearsalId($value)
 * @method static Builder|RehearsalAttendance whereUpdatedAt($value)
 * @method static Builder|RehearsalAttendance whereUserId($value)
 * @mixin \Eloquent
 */
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
     * @param Builder $query
     * @param Integer $rehearsalId
     * @return Builder
     */
    public function scopeForRehearsal ($query, $rehearsalId) {
        return $query->where('rehearsal_id', $rehearsalId);
    }
}
