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

    public function attendance(){
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
        $adminAreas = [
            'rehearsal' => 'can_plan_rehearsal',
            'gig'       => 'can_plan_gig',
            'configure' => 'can_configure_system',
        ];

        if (null === $area || !array_key_exists($area, $adminAreas)) {
            return false;
        }

        return $this->roles()->orderBy($adminAreas[$area], 'desc')->first()->getAttributeValue($adminAreas[$area]);
    }

    public static function getMusicalLeader() {
        return User::whereHas('roles', function ($query) {
            $query->where('musical_leadership', 1);
        })->get();
    }

    public static function getUsersOfVoice($voice_id) {
        return User::where(['voice_id' => $voice_id, 'last_echo' => Semester::current()->id])->get();
    }
}
