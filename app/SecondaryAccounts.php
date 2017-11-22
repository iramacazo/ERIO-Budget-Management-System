<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondaryAccounts extends Model
{
    //
    public function primary_accounts(){
        return $this->belongsTo("App\PrimaryAccounts", 'account_id', 'id');
    }

    public function list_of_secondary_accounts(){
        return $this->hasMany("App\ListOfSecondaryAccounts", 'list_id', 'id');
    }

    public function tertiary_accounts(){
        return $this->hasMany("App\TertiaryAccounts", 'subaccount_id', 'id');
    }
}
