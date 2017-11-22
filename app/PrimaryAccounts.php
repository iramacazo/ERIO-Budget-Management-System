<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimaryAccounts extends Model
{
    //
    public function list_of_primary_accounts(){
        return $this->hasMany("App\ListOfPrimaryAccounts", 'account_id', 'id');
    }

    public function secondary_accounts(){
        return $this->hasMany("App\SecondaryAccounts", 'account_id', 'id');
    }
}
