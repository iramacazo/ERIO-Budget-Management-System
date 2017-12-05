<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionForm extends Model
{
    public function entries(){
        return $this->hasMany('App\MaterialRequisitionFormEntries', 'mrf_id', 'id');
    }

    public function list_PA(){
        return $this->belongsTo('App\ListOfPrimaryAccounts', 'list_pa_id', 'id');
    }

    public function requester(){
        return $this->belongsTo('App\User', 'requested_by', 'id');
    }
}
