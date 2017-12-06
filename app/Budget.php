<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public function list_of_primary_accounts(){
        return $this->hasMany('App\ListOfPrimaryAccounts', 'budget_id', 'id');
    }
}
