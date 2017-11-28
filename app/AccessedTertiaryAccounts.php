<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessedTertiaryAccounts extends Model
{
    public function accessor(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function approver(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function accessedTertiaryAccount(){
        return $this->belongsTo('App\ListOfTertiaryAccounts', 'list_id', 'id');
    }

}
