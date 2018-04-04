<?php

namespace App\Models;

use App\Http\Controllers\SemesterController;
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
    private static $current_semester = null;

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
        // Memorize current semester to save queries.
        if (self::$current_semester === null) {
            $today = Carbon::today();
            self::$current_semester = Semester::where('start', '<=', $today)->where('end', '>=', $today)->firstOrFail();
        }
        return self::$current_semester;
    }

    public static function nextSemester() {
        $today_in_six_months = Carbon::today()->addMonths(6);
        return (new SemesterController())->getSemester($today_in_six_months);
    }

    public static function last() {
        return Semester::orderBy('end', 'desc')->firstOrFail();
    }
}
