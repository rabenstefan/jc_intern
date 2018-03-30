<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
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
class User extends Authenticatable
{
    use SoftDeletes;

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
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
    protected  $adminAreas = [
        'rehearsal' => 'can_plan_rehearsal',
        'gig'       => 'can_plan_gig',
        'sheet'     => 'can_organise_sheets',
        'configure' => 'can_configure_system',
    ];

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

    public function isAdmin($area = 'configure') {
        if (null === $area || !array_key_exists($area, $this->adminAreas)) {
            return false;
        }

        // Get roles matching the areas flag, sort them descending so a "mightier" role has precedence.
        $matching_role = $this->roles()->orderBy($this->adminAreas[$area], 'desc')->first();
        return (null !== $matching_role) && ($matching_role->getAttributeValue($this->adminAreas[$area]));
    }

    public function adminOnlyOwnVoice($area) {
        if (null === $area || !array_key_exists($area, $this->adminAreas)) {
            return false;
        }

        // Get roles matching area flag, sort ascending for only_own_voice so a "mightier" role has precedence.
        $matching_role = $this->roles()->where($this->adminAreas[$area], true)->orderBy('only_own_voice', 'asc')->first();
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

        if (null === $attendance) {
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

        if (null === $attendance) {
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
     * @param bool $with_old include rehearsals prior to this semester
     * @return int Number of missed rehearsals
     */
    public function missedRehearsalsCount($unexcused_only = false, $with_old=false) {
        //TODO: Need ability to show missed rehearsals of the current semester so far.
        //TODO: Count according to "weight" of rehearsal
        $all_rehearsals = Rehearsal::all(['id'], $with_old);
        $conditions = [['missed', 1]];
        if (true === $unexcused_only) {
            array_push($conditions, ['attendance', 0]);
        }
        return $this->rehearsal_attendances()->where($conditions)->whereIn('rehearsal_id', $all_rehearsals)->count();
    }

    public static function getMusicalLeader() {
        return User::whereHas('roles', function ($query) {
            $query->where('musical_leadership', 1);
        })->get();
    }

    public static function getUsersOfVoice($voiceId) {
        return User::where(['voice_id' => $voiceId, 'last_echo' => Semester::current()->id])->get();
    }

    public function scopeCurrent($query){
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
     * @return User|\Eloquent[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function all($columns = ['*'], $with_old = false) {
        if ($with_old) {
            return parent::all($columns);
        } else {
            return parent::where('last_echo', Semester::current()->id)->get($columns);
        }
    }

    public function getNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAbbreviatedNameAttribute(){
        return $this->first_name . ' ' . str_shorten($this->last_name, 1) . '.';
    }
}
