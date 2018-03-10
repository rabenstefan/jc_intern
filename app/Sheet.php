<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    public function users() {
        return $this->belongsToMany('App\User')->withPivot('number', 'status');
    }

    public function borrowed(){
        return $this->wherePivot('status', '=', 'borrowed');
    }

    public function getBorrowedAttribute(){
        dd($this->users);
    }
}
