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

    // For eager loading and memorizing the result.
    private static $child_voices = null;
    private static $parent_voices = [];

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

    /**
     * Get the root voice.
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function getRoot(){
        return Voice::whereNull('super_group' )->first();
    }

    /**
     * Get all voices, that are not the root voice.
     *
     * @return Voice[]
     */
    public static function getChildVoices() {
        if (null === self::$child_voices) {
            self::$child_voices = Voice::with('parent')->where('child_group', true)->get(
                ['id', 'name', 'super_group', 'child_group']
            );
        }
        return self::$child_voices;
    }

    /**
     * Get the distinct parent voices of the given set of voices.
     *
     * @return Collection
     */
    public static function getParentVoices() {
        return self::whereNotNull('super_group')->where('child_group', false)->with(['users', 'children.users'])->get();
    }
}
