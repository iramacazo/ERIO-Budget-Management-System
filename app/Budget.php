<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $dates = ['start_range', 'end_range'];
    public function list_of_primary_accounts(){
        return $this->hasMany('App\ListOfPrimaryAccounts', 'budget_id', 'id');
    }
}
