<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\BeerCount
 *
 * @property int $id
 * @property int $user_id
 * @property string $date
 * @property int $amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static Builder|BeerCount whereAmount($value)
 * @method static Builder|BeerCount whereCreatedAt($value)
 * @method static Builder|BeerCount whereDate($value)
 * @method static Builder|BeerCount whereId($value)
 * @method static Builder|BeerCount whereUpdatedAt($value)
 * @method static Builder|BeerCount whereUserId($value)
 * @mixin \Eloquent
 */
class BeerCount extends Model
{
    public function user () {
        return $this->hasOne('App\Models\User');
    }
}
