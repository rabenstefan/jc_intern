<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Voice extends \Eloquent {
    protected $casts = [
        'child_group' => 'boolean'
    ];

    public function users() {
        return $this->hasMany('App\User');
    }

    public function super_group() {
        return $this->belongsTo('App\Voice', 'super_group', 'id');
    }

    public function children() {
        return $this->hasMany('App\Voice', 'super_group', 'id');
    }

    public function rehearsals() {
        return $this->hasMany('App\Rehearsal');
    }

    public static function getChildVoices() {
        return Voice::all()->where('child_group', true);
    }

    /**
     * Get the distinct parent voices of the given set of voices.
     *
     * @param Collection $voices
     * @return Collection
     */
    public static function getParentVoices(Collection $voices) {
        $parents = new Collection();

        $voices->load('super_group');

        foreach ($voices as $voice) {
            $super_group = $voice->super_group()->first(); // Should only be one, but to be on the safe site we use first().
            $parents->put($super_group->id, $super_group); // Put in collection of parents.
        }

        return $parents;
    }
}
