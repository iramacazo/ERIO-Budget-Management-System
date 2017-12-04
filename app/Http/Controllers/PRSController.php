<?php

namespace App\Http\Controllers;

use App\BookstoreRequisitionForm;
use App\JournalEntries;
use App\MaterialRequisitionFormEntries;
use App\OtherTransactions;
use App\PaymentRequisitionSlips;
use Illuminate\Http\Request;

class PRSController extends Controller
{
    public function savePRS(Request $request){
        $this->validate($request, [
            'code' => 'required|unique:payment_requisition_slips,code'
        ]);

        $prs = new PaymentRequisitionSlips;
        $prs->code = $request->code;
        $prs->save();

        $entry = JournalEntries::find($request->id);
        $entry->prs_id = $prs->id;
        $entry->save();

        return view('prsPrint')->with('prs', $entry);
    }

    public function prsForm(Request $request){
        return view('prsForm')->with('id', $request->id);
    }

    public function getPRS(){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $id = $budget->id;
        $entries = JournalEntries::whereNotNull('prs_id')->get();


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

        $sorted = $entries->sortBy('created_at');

        return view('prsView')->with('entries', $sorted);
    }
}
