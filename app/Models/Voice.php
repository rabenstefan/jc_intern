<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\Voice
 *
 * @property int $id
 * @property string $name
 * @property int|null $super_group
 * @property bool $child_group
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Voice[] $children
 * @property-read Voice|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rehearsal[] $rehearsals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static Builder|Voice whereChildGroup($value)
 * @method static Builder|Voice whereCreatedAt($value)
 * @method static Builder|Voice whereId($value)
 * @method static Builder|Voice whereName($value)
 * @method static Builder|Voice whereSuperGroup($value)
 * @method static Builder|Voice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Voice extends \Eloquent {
    protected $casts = [
        'child_group' => 'boolean'
    ];

    public function users() {
        return $this->hasMany('App\Models\User');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Voice', 'super_group', 'id');
    }

    public function children() {
        return $this->hasMany('App\Models\Voice', 'super_group', 'id');
    }

    public function rehearsals() {
        return $this->hasMany('App\Models\Rehearsal');
    }

    public static function getChildVoices() {
        return Voice::all()->where('child_group', true);
    }

    public static function getRoot(){
        return Voice::whereNull('super_group' )->first();
    }

    /**
     * Get the distinct parent voices of the given set of voices.
     *
     * @param Voice $voices
     * @return Collection
     */
    public static function getParentVoices($voices) {
        $parents = new Collection();

        $voices->load('super_group');

        foreach ($voices as $voice) {
            $super_group = $voice->super_group()->first(); // Should only be one, but to be on the safe site we use first().
            $parents->put($super_group->id, $super_group); // Put in collection of parents.
        }

        return $parents;
    }
}
