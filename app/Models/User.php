<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $birthday
 * @property string|null $phone
 * @property string|null $address_street
 * @property int|null $address_zip
 * @property string|null $address_city
 * @property int $sheets_deposit_returned
 * @property int $voice_id
 * @property int|null $last_echo
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BeerCount[] $beer_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sheet[] $borrowed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sheet[] $bought
 * @property-read string $name
 * @property-read string $abbreviated_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GigAttendance[] $gig_attendances
 * @property-read \App\Models\Semester|null $last_echoed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sheet[] $lost
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RehearsalAttendance[] $rehearsal_attendances
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SemesterFee[] $semester_fees
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sheet[] $sheets
 * @property-read \App\Models\Voice $voice
 * @method static Builder|User current()
 * @method static bool|null forceDelete()
 * @method static Builder|User ofVoice($voiceId)
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|User whereAddressCity($value)
 * @method static Builder|User whereAddressStreet($value)
 * @method static Builder|User whereAddressZip($value)
 * @method static Builder|User whereBirthday($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastEcho($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSheetsDepositReturned($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereVoiceId($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable {
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'birthday',
        'phone',
        'address_street',
        'address_zip',
        'address_city',
        'sheets_deposit_returned',
        'voice_id',
        'last_echo',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pseudo_password'
    ];

    /**
     * Allow soft deletes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Casts flag to boolean.
     *
     * @var array
     */
    protected $casts = [
        'birthday' => 'date',
        'sheet_deposit_returned' => 'boolean',
    ];

    /**
     * Mapping of area names to flags.
     *
     * @var array
     */
    protected  $admin_areas = [
        'rehearsal' => 'can_plan_rehearsal',
        'gig'       => 'can_plan_gig',
        'sheet'     => 'can_organise_sheets',
        'configure' => 'can_configure_system',
    ];

    private $is_admin = [
        'rehearsal' => null,
        'gig'       => null,
        'sheet'     => null,
        'configure' => null,
    ];

    private static $all_current_users = null;

    /*
     * Model all relationships.
     */
    public function voice() {
        return $this->belongsTo('App\Models\Voice');
    }

    public function last_echo() {
        return $this->belongsTo('App\Models\Semester', 'last_echo');
    }

    public function gig_attendances(){
        return $this->hasMany('App\Models\GigAttendance');
    }

    public function rehearsal_attendances(){
        return $this->hasMany('App\Models\RehearsalAttendance');
    }

    public function beer_count() {
        return $this->hasMany('App\Models\BeerCount');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function semester_fees() {
        return $this->hasMany('App\Models\SemesterFee');
    }

    public function sheets() {
        return $this->belongsToMany('App\Models\Sheet');
    }

    public function borrowed(){
        return $this->belongsToMany('App\Models\Sheet')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_BORROWED);
    }

    public function lost(){
        return $this->belongsToMany('App\Models\Sheet')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_LOST);
    }

    public function bought(){
        return $this->belongsToMany('App\Models\Sheet')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_BOUGHT);
    }

    /*
     * Define getter and setter.
     */
    public function getFirstNameAttribute($value) {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value) {
        return ucfirst($value);
    }

    public function getBirthdayAttribute($value) {
        return $value;
    }

    /**
     * Return if a user is an admin in the specified area.
     *
     * @param string $area
     * @return bool
     */
    public function isAdmin($area = 'configure') {
        if (null === $area || !array_key_exists($area, $this->admin_areas)) {
            return false;
        }

        // Look up in table for saving queries.
        if (null === $this->is_admin[$area]) {
            // Get roles matching the areas flag, return just the first
            $matching_role = $this->roles->filter(function ($value, $key) use ($area) {
                return $value->attributes[$this->admin_areas[$area]] == true;
            })->first();

            $this->is_admin[$area] = (null !== $matching_role);
        }

        // Otherwise, null will be accepted.
        return true === $this->is_admin[$area];
    }

    public function isVoiceLeader() {
        return null !== $this->roles->filter(function ($value, $key) {
            return $value->id == 2;
        })->first();
    }

    public function adminOnlyOwnVoice($area) {
        if (null === $area || !array_key_exists($area, $this->admin_areas)) {
            return false;
        }

        // Get roles matching area flag, sort ascending for only_own_voice so a "mightier" role has precedence.
        $matching_role = $this->roles()->where($this->admin_areas[$area], true)->orderBy('only_own_voice', 'asc')->first();
        return (null !== $matching_role) && ($matching_role->getAttributeValue('only_own_voice'));
    }

    /**
     * Function to determine if a user has missed (or will miss) a rehearsal.
     *
     * @param $rehearsalId
     * @return bool
     */
    public function missedRehearsal($rehearsalId) {
        // Get all currently available attendances and filter by the rehearsal ID. Take the first find.
        $attendance = $this->rehearsal_attendances->filter(function ($value, $key) use ($rehearsalId) {
            return $value->rehearsal_id == $rehearsalId;
        })->first();

        if (null === $attendance || null === $attendance->missed) {
            // If there is no attendance return "missed".
            return true;
        } else {
            return $attendance->missed;
        }
    }

    /**
     * Function to determine if a user has excused himself for a rehearsal.
     *
     * @param $rehearsalId
     * @return bool
     */
    public function excusedRehearsal($rehearsalId) {
        // Get all currently available attendances and filter by the rehearsal ID. Take the first find.
        $attendance = $this->rehearsal_attendances->filter(function ($value, $key) use ($rehearsalId) {
            return $value->rehearsal_id == $rehearsalId;
        })->first();

        if (null === $attendance || null === $attendance->attendance) {
            // If there is no attendance return "missed".
            return false;
        } else {
            // For No and maybe, return 'excused'
            return $attendance->attendance !== \Config::get('enums.attendances')['yes'];
        }
    }

    /**
     * Count how many rehearsals have been missed (possibly including the future)
     *
     * @param bool $unexcused_only Only count if not excused
     * @param bool $with_old include rehearsals prior to today
     * @param bool $with_new include rehearsals in the future
     * @param bool $current_only restrict to current semester
     * @return float Number of missed rehearsals
     */
    public function missedRehearsalsCount($unexcused_only = false, $with_old=true, $with_new=false, $current_only = true, $mandatory_only = true, $consider_weight = true) {
        //TODO: Count according to "weight" of rehearsal
        // TODO: 'weight' and 'mandatory' have overlapping uses. (weight=0 == mandatory=false)
        //TODO: Optimize!
        $rehearsals = Rehearsal::all(['id', 'mandatory', 'weight'], $with_old, false, $with_new, $current_only);

        if ($mandatory_only) {
            $rehearsals = $rehearsals->where('mandatory', true);
        }


        $rehearsals = $rehearsals->filter(function($rehearsal) {
            return $this->missedRehearsal($rehearsal->id);
        });

        if ($unexcused_only) {
            $rehearsals = $rehearsals->filter(function($rehearsal) {
                return !$this->excusedRehearsal($rehearsal->id);
            });
        }

        $count = 0.0;
        if ($consider_weight) {
            // Count regular weighted rehearsals first to improve performance
            $count += $rehearsals->where('weight', 1.0)->count();

            $rehearsals->filter(function($rehearsal) use (&$count) {
                if ($rehearsal->weight != 1.0) { //TODO: add some more checks to ensure there is no weird stuff in $rehearsal->weight
                    $count += $rehearsal->weight;
                }
            });
        } else {
            // This will count rehearsals that carry weight zero
            $count = $rehearsals->count();
        }
        return $count;
    }

    /**
     * Discloses how often a user has been missing from rehearsals
     *
     * @param bool $with_old
     * @param bool $with_new
     * @param bool $current_only
     * @param bool $mandatory_only
     * @param bool $consider_weight
     * @return array whose keys are ['total', 'unexcused', 'excused']
     */
    public function missedRehearsalsCountArray($with_old=true, $with_new=false, $current_only = true, $mandatory_only = true, $consider_weight = true) {
        $count = [
            'total' => $this->missedRehearsalsCount(false, $with_old, $with_new, $current_only, $mandatory_only, $consider_weight),
            'unexcused' => $this->missedRehearsalsCount(true, $with_old, $with_new, $current_only, $mandatory_only, $consider_weight)
        ];
        $count['excused'] = $count['total'] - $count['unexcused'];

        return $count;
    }

    /**
     * Check if user is over missed rehearsal limit for current semester.
     *
     * Default limits are configured in enums.allowed_missed_rehearsals
     *
     * @param array $custom_limits whose keys are a subset of ['total', 'excused', 'unexcused']
     * @return bool
     */
    public function isOverMissingRehearsalsLimit($custom_limits = Array(), $with_old=true, $with_new=false, $current_only = true, $mandatory_only = true, $consider_weight = true) {
        $count = $this->missedRehearsalsCountArray($with_old, $with_new, $current_only, $mandatory_only, $consider_weight);

        return self::checkRehearsalsLimit($count, $custom_limits);
    }

    public static function checkRehearsalsLimit($missed_rehearsal_count_array, $custom_limits = Array()) {
        $over_limit = false;
        $limits = $custom_limits;
        if (empty($limits)) {
            $limits = \Config::get('enums.allowed_missed_rehearsals');
        }

        foreach(array_keys($limits) as $key) {
            // Comparing a float/double and an int. What could go wrong?
            if ($missed_rehearsal_count_array[$key] > $limits[$key]) {
                $over_limit = true;
                break;
            }
        }

        return $over_limit;
    }

    public static function getMusicalLeader() {
        return User::whereHas('roles', function ($query) {
            $query->where('musical_leadership', 1);
        })->get();
    }

    public static function getUsersOfVoice($voice_id, $with_attendances = false) {
        if (null === self::$all_current_users) {
            self::$all_current_users = self::all(['*'], false, $with_attendances);
        }

        return self::$all_current_users->where('voice_id', $voice_id);
    }

    public function scopeCurrent($query){
        //TODO: should also return users who echoed for a future semester. Or rework with many-to-many between users and voices
        return $query->where('last_echo', Semester::current()->id);
    }

    public function scopeOfVoice($query, $voiceId) {
        return $query->where('voice_id', $voiceId);
    }

    /**
     * No need for old users usually.
     *
     * @param array $columns
     * @param bool $with_old
     * @param bool $with_attendances
     * @return User|\Eloquent[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function all($columns = ['*'], $with_old = false, $with_attendances = false) {
        $eager_load_relations = ['roles'];

        // Should we preload more relations?
        if ($with_attendances) {
            $eager_load_relations[] = 'gig_attendances.user';
            $eager_load_relations[] = 'rehearsal_attendances.user';
        }

        if ($with_old) {
            return parent::with($eager_load_relations)->get($columns);
        } else {
            // Cache all without old.
            if (null === self::$all_current_users) {
                //TODO: handle the case when we are in between semesters
                //self::$all_current_users = parent::with($eager_load_relations)->where('last_echo', Semester::nextSemester()->id)->get($columns);
                self::$all_current_users = parent::with($eager_load_relations)->where('last_echo', Semester::current()->id)->get($columns);
            }
            return self::$all_current_users;
        }
    }

    public function getNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAbbreviatedNameAttribute(){
        return $this->first_name . ' ' . str_shorten($this->last_name, 1) . '.';
    }
}
