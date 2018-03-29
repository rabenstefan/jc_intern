<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $label
 * @property bool $can_plan_rehearsal
 * @property bool $can_plan_gig
 * @property int $can_organise_sheets
 * @property bool $can_send_mail
 * @property bool $can_configure_system
 * @property bool $only_own_voice
 * @property int $musical_leadership
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static Builder|Role whereCanConfigureSystem($value)
 * @method static Builder|Role whereCanOrganiseSheets($value)
 * @method static Builder|Role whereCanPlanGig($value)
 * @method static Builder|Role whereCanPlanRehearsal($value)
 * @method static Builder|Role whereCanSendMail($value)
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereLabel($value)
 * @method static Builder|Role whereMusicalLeadership($value)
 * @method static Builder|Role whereOnlyOwnVoice($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends \Eloquent {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'can_plan_rehearsal',
        'can_plan_gig',
        'can_send_mail',
        'can_configure_system',
        'only_own_voice',
    ];
    
    protected $casts = [
        'can_plan_rehearsal'   => 'boolean',
        'can_plan_gig'         => 'boolean',
        'can_send_mail'        => 'boolean',
        'can_configure_system' => 'boolean',
        'only_own_voice'       => 'boolean',
    ];

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }
}
