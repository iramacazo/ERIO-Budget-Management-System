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

    public function thisUpperAccount(){
        return $this->belongsTo('App\AccessedPrimaryAccounts', 'accessedPA_id', 'id');
    }

    public function thisLowerAccounts(){
        return $this->hasMany('App\AccessedTertiaryAccounts', 'accessedSA_id', 'id');
    }
}
