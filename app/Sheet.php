<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{

    const STATUS_BORROWED   = 'borrowed';
    const STATUS_LOST       = 'lost';
    const STATUS_BOUGHT     = 'bought';
    const STATUS_AVAILABLE  = 'available';

    public function users() {
        return $this->belongsToMany('App\User')->withPivot('number', 'status');
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

    public function getNextFreeNumber(){
        $users = $this->users;
        $numbers  = [];
        foreach ($users as $user)
            $numbers[] = $user->pivot->number;

        rsort($numbers);
        return $numbers[0] + 1;
    }
}
