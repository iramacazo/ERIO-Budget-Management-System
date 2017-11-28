<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessedSecondaryAccounts extends Model
{
    public function accessor(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function approver(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function accessedSecondaryAccount(){
        return $this->belongsTo('App\ListOfSecondaryAccounts', 'list_id', 'id');
    }
}
