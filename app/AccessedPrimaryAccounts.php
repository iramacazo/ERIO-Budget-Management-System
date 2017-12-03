<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessedPrimaryAccounts extends Model
{
    public function accessor(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function approver(){
        return $this->belongsTo('App\User', 'approved_by', 'id');
    }

    public function accessedPrimaryAccount(){
        return $this->belongsTo('App\ListOfPrimaryAccounts', 'list_id', 'id');
    }
}
