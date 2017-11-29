<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequisitionSlips extends Model
{
    public function entry(){
        return $this->hasOne('App\JournalEntries', 'prs_id', 'id');
    }
}
