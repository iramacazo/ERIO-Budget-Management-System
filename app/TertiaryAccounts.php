<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TertiaryAccounts extends Model
{
    //
    public function secondary_accounts(){
        return $this->belongsTo("App\SecondaryAccounts", 'subaccount_id', 'id');
    }

    public function list_of_tertiary_accounts(){
        return $this->hasMany("App\ListOfTertiaryAccounts", 'account_id', 'id');
    }
}
