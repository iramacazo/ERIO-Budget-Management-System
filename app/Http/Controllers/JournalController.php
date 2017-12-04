<?php

namespace App\Http\Controllers;

use App\Budget;
use App\JournalEntries;
use App\ListOfPrimaryAccounts;
use App\PaymentRequisitionSlips;
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

            //remove all that is not of the latest budget
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

    public function primaryLedger($primary = null){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $id = $budget->id;
        $entries = JournalEntries::all();

        if($primary == null){
            $pa_id = ListOfPrimaryAccounts::where('budget_id', $id)->first();
        } else {
            $pa_id = $primary;
        }

        $entries->filter(function ($entry) use ($pa_id){
           if( $entry->mrf_entry_id != null ){
               return $entry->mrf_entry->mrf->list_PA->id == $pa_id;
           } else if( $entry->brf_id != null ){
               if( $entry->list_pa_id != null){
                   return $entry->brf->list_PA->id == $pa_id;
               } else if( $entry->list_sa_id != null){
                   return $entry->brf->list_SA->list_of_primary_accounts->id == $pa_id;
               } else if( $entry->list_ta_id != null){
                   return $entry->brf->list_TA->list_of_secondary_accounts->list_of_primary_accounts->id == $pa_id;
               }
           } else if( $entry->pcv_id != null ){
               if( $entry->pcv->list_pa_id != null){
                   return $entry->pcv->list_PA->id == $pa_id;
               } else if( $entry->pcv->list_sa_id != null){
                   return $entry->pcv->list_SA->list_of_primary_accounts->id == $pa_id;
               } else if( $entry->pcv->list_ta_id != null){
                   return $entry->pcv->list_TA->list_of_secondary_accounts->list_of_primary_accounts->id == $pa_id;
               }
           } else if( $entry->transaction_id != null){
                if( $entry->otherTransactions->list_pa_id != null){
                    return $entry->otherTransactions->list_PA->id == $pa_id;
                } else if( $entry->otherTransactions->list_sa_id != null){
                    return $entry->otherTransactions->list_SA->list_of_primary_accounts->id == $pa_id;
                } else if( $entry->otherTransactions->list_ta_id != null){
                    return $entry->otherTransactions->list_TA->list_of_secondary_accounts->list_of_primary_accounts->id == $pa_id;
                }
           }
        });

        $sorted = $entries->sortBy('created_at');

        return view('ledger')->with('entries', $sorted);
    }
}
