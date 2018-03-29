<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * App\Models\Semester
 *
 * @property int $id
 * @property string $start
 * @property string $end
 * @property string $label
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $echoed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gig[] $gigs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rehearsal[] $rehearsals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SemesterFee[] $semester_fees
 * @method static Builder|Semester whereCreatedAt($value)
 * @method static Builder|Semester whereEnd($value)
 * @method static Builder|Semester whereId($value)
 * @method static Builder|Semester whereLabel($value)
 * @method static Builder|Semester whereStart($value)
 * @method static Builder|Semester whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Semester extends \Eloquent {
    public function gigs() {
        return $this->hasMany('App\Models\Gig');
    }

    public function rehearsals() {
        return $this->hasMany('App\Models\Rehearsal');
    }

    public function echoed() {
        return $this->hasMany('App\Models\User', 'last_echo');
    }

    public function semester_fees() {
        return $this->hasMany('App\Models\SemesterFee');
    }

    public static function current() {
        $today = Carbon::today();
        return Semester::where('start', '<=', $today)->where('end', '>=', $today)->firstOrFail();
    }

    public static function last() {
        return Semester::orderBy('end', 'desc')->firstOrFail();
    }
}
