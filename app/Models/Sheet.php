<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Sheet
 *
 * @property int $id
 * @property string $label
 * @property int $amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $borrowed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $bought
 * @property-read mixed $available_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $lost
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static Builder|Sheet whereAmount($value)
 * @method static Builder|Sheet whereCreatedAt($value)
 * @method static Builder|Sheet whereId($value)
 * @method static Builder|Sheet whereLabel($value)
 * @method static Builder|Sheet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sheet extends Model {

    const STATUS_BORROWED   = 'borrowed';
    const STATUS_LOST       = 'lost';
    const STATUS_BOUGHT     = 'bought';

    public static $statuses = [
        Sheet::STATUS_BORROWED,
        Sheet::STATUS_LOST,
        Sheet::STATUS_BOUGHT
    ];

    public static $rules = [
        'label' => 'required|unique:sheets',
        'amount' => 'required|numeric'
    ];

    protected $fillable = ['label', 'amount'];

    public function users() {
        return $this->belongsToMany('App\Models\User')->withPivot('id', 'number', 'status');
    }

    public function borrowed(){
        return $this->belongsToMany('App\Models\User')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_BORROWED);
    }

    public function lost(){
        return $this->belongsToMany('App\Models\User')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_LOST);
    }

    public function bought(){
        return $this->belongsToMany('App\Models\User')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_BOUGHT);
    }

    public function getAvailableCountAttribute(){
        return $this->amount - $this->borrowed->count() - $this->lost->count() - $this->bought->count();
    }

    public function numberExists($number, $oldNumber){
        $users = $this->users->toArray();
        $numbers = [];
        foreach ($users as $user){
            if ($user['pivot']['number'] != $oldNumber){
                $numbers[] = $user['pivot']['number'];
            }
        }
        return in_array($number, $numbers);
    }

    //TODO: Nope nope nope.
    public function getNextFreeNumber() {
        $sheetUser = DB::table('sheet_user')
            ->select('number')
            ->where('sheet_id', '=', $this->id)
            ->orderBy('number', 'desc')
            ->first();

        if (!$sheetUser)
            return 1;
        else
            return intval($sheetUser->number) +1;
    }
}
