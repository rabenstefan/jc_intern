<?php

namespace App;

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
        return $this->belongsToMany('App\User');
    }
}
