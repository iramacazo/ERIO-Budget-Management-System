<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListOfTertiaryAccounts extends Model
{
    //
    public function tertiary_accounts(){
        return $this->belongsTo("App\TertiaryAccounts", 'account_id', 'id');
    }

    public function list_of_secondary_accounts(){
        return $this->belongsTo("App\ListOfSecondaryAccounts", 'list_id', 'id');
    }
}
