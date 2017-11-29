<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntries extends Model
{
    public function petty_cash_voucher(){
        return $this->hasOne('App\PettyCashVoucher', 'pcv_id', 'id');
    }

    public function brf(){
        return $this->hasOne('App\BookstoreRequisitionForm', 'brf_id', 'id');
    }

    public function mrf_entries(){
        return $this->belongsTo('App\MaterialRequisitionFormEntries', 'entry_id', 'id');
    }

    public function otherTransactions(){
        return $this->belongsTo('App\OtherTransactions', 'entry_id', 'id');
    }

    public function prs(){
        return $this->belongsTo('App\PaymentRequisitionSlips', 'prs_id', 'id');
    }
}
