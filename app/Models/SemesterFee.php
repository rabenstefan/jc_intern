<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SemesterFee
 *
 * @property int $id
 * @property int $user_id
 * @property int $semester_id
 * @property bool $paid
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Semester $semester
 * @property-read \App\Models\User $user
 * @method static Builder|SemesterFee whereCreatedAt($value)
 * @method static Builder|SemesterFee whereId($value)
 * @method static Builder|SemesterFee wherePaid($value)
 * @method static Builder|SemesterFee whereSemesterId($value)
 * @method static Builder|SemesterFee whereUpdatedAt($value)
 * @method static Builder|SemesterFee whereUserId($value)
 * @mixin \Eloquent
 */
class SemesterFee extends Model {
    protected $casts = [
        'paid' => 'boolean',
    ];

    public function semester() {
        return $this->belongsTo('App\Models\Semester');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
