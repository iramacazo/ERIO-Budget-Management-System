<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntries extends Model
{
    public function petty_cash_voucher(){
        return $this->belongsTo('App\PettyCashVoucher', 'pcv_id', 'id');
    }

    public function brf(){
        return $this->belongsTo('App\BookstoreRequisitionForm', 'brf_id', 'id');
    }

    public function mrf_entry(){
        return $this->belongsTo('App\MaterialRequisitionFormEntries', 'mrf_entry_id', 'id');
    }

    public function otherTransactions(){
        return $this->belongsTo('App\OtherTransactions', 'transaction_id', 'id');
    }

    public function prs(){
        return $this->belongsTo('App\PaymentRequisitionSlips', 'prs_id', 'id');
    }
}
