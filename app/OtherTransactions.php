<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherTransactions extends Model
{
    public function list_PA(){
        return $this->belongsTo('App\ListOfPrimaryAccounts', 'list_pa_id', 'id');
    }

    public function list_SA(){
        return $this->belongsTo('App\ListOfSecondaryAccounts', 'list_sa_id', 'id');
    }

    public function list_TA(){
        return $this->belongsTo('App\ListOfTertiaryAccounts', 'list_ta_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function entries(){
        return $this->hasMany('App\JournalEntries', 'entry_id', 'id');
    }
}
