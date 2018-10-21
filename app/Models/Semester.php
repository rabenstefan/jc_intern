<?php

namespace App\Models;

use App\Http\Controllers\SemesterController;
use \Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            try {
                self::$current_semester = Semester::where('start', '<=', $today)->where('end', '>=', $today)->firstOrFail();
            } catch (ModelNotFoundException $error) {
                // No fitting semester found: Add one and try again. This should never happen in production.
                if ((new SemesterController())->generateNewSemester()) {
                    self::$current_semester = Semester::where('start', '<=', $today)->where('end', '>=', $today)->firstOrFail();
                }
            }
        }
        return self::$current_semester;
    }

    /**
     * @return Semester[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function currentList() {
        return self::where('end', '>=', Carbon::today())->get(['id']);
    }

    public static function futureList() {
        return self::where('end', '>', self::current()->end);
    }

    public static function next() {
        $day_in_new_semester = (new Carbon(self::current()->end))->addDays(2);
        return (new SemesterController())->getSemester($day_in_new_semester);
    }

    public static function last() {
        return Semester::orderBy('end', 'desc')->firstOrFail();
    }
}
