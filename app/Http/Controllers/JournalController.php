<?php

namespace App\Http\Controllers;

use App\Budget;
use App\JournalEntries;
use App\ListOfPrimaryAccounts;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class JournalController extends Controller
{
    public function primaryAccounts(){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $primary = ListOfPrimaryAccounts::where('budget_id', $budget->id)->get();

        return view('pickPrimary')->with('primary', $primary);
    }

    public function journalPrimary(){
            $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
            $id = $budget->id;
            $entries = JournalEntries::all();

            //remove all that is not $id
            $entries->reject(function ($entry) use ($id){
                if($entry->mrf_entry_id != null){
                    return $entry->mrf_entry->mrf->list_PA->budget_id != $id;
                } else if($entry->brf_id != null){
                    if( $entry->brf->list_pa_id != null){
                        return $entry->brf->list_PA->budget_id != $id;
                    } else if( $entry->brf->list_sa_id != null){
                        return $entry->brf->list_SA->list_of_primary_accounts->budget_id != $id;
                    } else if( $entry->brf->list_ta_id != null){
                        return $entry->brf->list_TA->list_of_secondary_accounts->list_of_primary_accounts->budget_id != $id;
                    }
                } else if($entry->pcv_id != null){
                    if($entry->pcv->list_pa_id != null){
                        return $entry->pcv->primary_account->budget_id != $id;
                    } else if($entry->pcv->list_sa_id != null){
                        return $entry->pcv->secondary_account->list_of_primary_accounts->budget_id != $id;
                    } else if($entry->pcv->list_ta_id != null){
                        return $entry->pcv->tertiary_account->list_of_secondary_accounts->list_of_primary_accounts->budget_id != $id;
                    }
                } else {
                    if($entry->otherTransactions->list_pa_id != null){
                        return $entry->otherTransactions->list_PA->budget_id != $id;
                    } else if($entry->otherTransactions->list_sa_id != null){
                        return $entry->otherTransactions->list_SA->list_of_primary_accounts->budget_id != $id;
                    } else if($entry->otherTransactions->list_ta_id != null){
                        return $entry->otherTransactions->list_TA->list_of_secondary_accounts->list_of_primary_accounts->budget_id != $id;
                    }
                }
            });

            //sort all entries by
            $sorted = $entries->sortBy('created_at');


            return view('generalJournalView')->with('entries', $sorted);
    }
}
