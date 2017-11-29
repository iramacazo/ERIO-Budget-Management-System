<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookstoreRequisitionFormEntries extends Model
{
    public function brf(){
        return $this->belongsTo('App\BookstoreRequisitionForm', 'brf_id', 'id');
    }
}
