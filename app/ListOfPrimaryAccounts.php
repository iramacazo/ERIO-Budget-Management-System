<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListOfPrimaryAccounts extends Model
{
    //
    public function primary_accounts(){
        return $this->belongsTo("App\PrimaryAccounts", 'account_id', 'id');
    }

    public function budgets(){
        return $this->belongsTo("App\Budget", 'budget_id', 'id');
    }

    public function list_of_secondary_accounts(){
        return $this->hasMany("App\ListOfSecondaryAccounts", 'list_id', 'id');
    }

    public function brf(){
        return $this->hasMany('App\BookstoreRequisitionForm', 'brf_id', 'id');
    }

    public function mrf(){
        return $this->hasMany('App\MaterialRequisitionForm', 'list_pa_id', 'id');
    }
}
