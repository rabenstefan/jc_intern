<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'configure' => 'can_configure_system',
    ];

    /*
     * Model all relationships.
     */
    public function voice() {
        return $this->belongsTo('App\Voice');
    }

    public function last_echo() {
        return $this->belongsTo('App\Semester', 'last_echo');
    }

    public function beer_count() {
        return $this->belongsToMany('App\BeerCount');
    }

    public function commitments() {
        return $this->belongsToMany('App\Commitment');
    }

    public function roles() {
        return $this->belongsToMany('App\Role');
    }

    public function semester_fees() {
        return $this->belongsToMany('App\SemesterFee');
    }

    public function sheets() {
        return $this->belongsToMany('App\Sheet');
    }

    public function attendances(){
        return $this->belongsToMany('App\Attendance');
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
        $attendance = $this->attendances->filter(function ($value, $key) use ($rehearsalId) {
            return $value->rehearsal_id == $rehearsalId;
        })->first();

        // If there is no attendance return "missed".
        return (null === $attendance) || $attendance->missed;
    }

    public static function getMusicalLeader() {
        return User::whereHas('roles', function ($query) {
            $query->where('musical_leadership', 1);
        })->get();
    }

    public static function getUsersOfVoice($voiceId) {
        return User::where(['voice_id' => $voiceId, 'last_echo' => Semester::current()->id])->get();
    }
}
