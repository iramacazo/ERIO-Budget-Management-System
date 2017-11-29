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
}
