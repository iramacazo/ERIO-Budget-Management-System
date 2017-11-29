<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormEntries extends Model
{
    public function mrf(){
        return $this->belongsTo('App\MaterialRequisitionForm', 'mrf_id', 'id');
    }

    public function list_SA(){
        return $this->belongsTo('App\ListOfSecondaryAccounts', 'list_sa_id', 'id');
    }

    public function list_TA(){
        return $this->belongsTo('App\ListOfTertiaryAccounts', 'list_ta_id', 'id');
    }

    public function entry(){
        return $this->hasOne('App\JournalEntries', 'entry_id', 'id');
    }
}
