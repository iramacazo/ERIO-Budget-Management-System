<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequisitionSlips extends Model
{
    public function entries(){
        return $this->hasMany('App\JournalEntries', 'prs_id', 'id');
    }
}
