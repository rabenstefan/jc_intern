<?php

namespace App;

class Role extends \Eloquent
{
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
