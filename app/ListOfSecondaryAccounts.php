<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListOfSecondaryAccounts extends Model
{
    //
    public function list_of_primary_accounts(){
        return $this->belongsTo("App\ListOfPrimaryAccounts", 'list_id', 'id');
    }

    public function secondary_accounts(){
        return $this->belongsTo("App\SecondaryAccounts", 'account_id', 'id');
    }

    public function list_of_tertiary_accounts(){
        return $this->hasMany("App\ListOfTertiaryAccounts", 'list_id', 'id');
    }

    public function brf(){
        return $this->hasMany('App\BookstoreRequisitionForm', 'brf_id', 'id');
    }

    public function mrf_entries(){
        return $this->hasMany('App\MaterialRequisitionFormEntries', 'list_sa_id', 'id');
    }

    public function otherTransactions(){
        return $this->hasMany('App\OtherTransactions', 'list_sa_id', 'id');
    }
}
