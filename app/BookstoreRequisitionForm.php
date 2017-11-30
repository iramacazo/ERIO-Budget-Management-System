<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookstoreRequisitionForm extends Model
{
    public function entries(){
        return $this->hasMany('App\BookstoreRequisitionFormEntries', 'brf_id', 'id');
    }

    public function journalEntry(){
        return $this->belongsTo('App\JournalEntries', 'entry_id', 'id');
    }

    public function listPrimary(){
        return $this->belongsTo('App\ListOfPrimaryAccounts', 'list_pa_id', 'id');
    }

    public function listSecondary(){
        return $this->belongsTo('App\ListOfSecondaryAccounts', 'list_sa_id', 'id');
    }

    public function listTertiary(){
        return $this->belongsTo('App\ListOfTertiaryAccounts', 'list_ta_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
