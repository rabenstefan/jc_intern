<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{

    const STATUS_BORROWED   = 'borrowed';
    const STATUS_LOST       = 'lost';
    const STATUS_BOUGHT     = 'bought';

    public static $statuses = [
        Sheet::STATUS_BORROWED,
        Sheet::STATUS_LOST,
        Sheet::STATUS_BOUGHT
    ];

    public function users() {
        return $this->belongsToMany('App\User')->withPivot('id', 'number', 'status');
    }

    public function borrowed(){
        return $this->belongsToMany('App\User')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_BORROWED);
    }

    public function lost(){
        return $this->belongsToMany('App\User')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_LOST);
    }

    public function bought(){
        return $this->belongsToMany('App\User')->withPivot('number', 'status')->wherePivot('status', '=', Sheet::STATUS_BOUGHT);
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

    public function getNextFreeNumber(){
        $users = $this->users;
        if ($users->count() == 0)
            return 1;
        $numbers  = [];
        foreach ($users as $user)
            $numbers[] = $user->pivot->number;

        rsort($numbers);
        return $numbers[0] + 1;
    }
}
