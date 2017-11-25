<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PettyCashVoucher extends Model
{
    //
    public function requested_by(){
        return $this->belongsTo("App\User", "requested_by", 'id');
    }

    public function approved_by(){
        return $this->belongsTo("App\User", "approved_by", 'id');
    }

    public function received_by(){
        return $this->belongsTo("App\User", "received_by", 'id');
    }

    public function primary_account(){
        return $this->belongsTo("App\ListOfPrimaryAccounts", "list_pa_id", 'id');
    }

    public function secondary_account(){
        return $this->belongsTo("App\ListOfSecondaryAccounts", "list_sa_id", 'id');
    }

    public function tertiary_account(){
        return $this->belongsTo('App\ListOfTertiaryAccounts', 'list_ta_id', 'id');
    }
}
