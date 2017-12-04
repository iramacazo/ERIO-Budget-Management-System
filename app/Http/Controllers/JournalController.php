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

            $entries = DB::table('journal_entries')
                            ->join('material_requisition_form_entries', 'material_requisition_form_entries.id',
                                '=', 'journal_entries.mrf_entry_id')
                            ->join('material_requisition_forms', 'material_requisition_forms.id',
                                  '=', 'material_requisition_form_entries.id')
                            ->join('bookstore_requisition_forms', 'bookstore_requisition_forms.id',
                                '=', 'journal_entries.brf_id')
                            ->join('other_transactions', 'other_transactions.id',
                                '=', 'journal_entries.transaction_id')
                            ->join('petty_cash_vouchers', 'petty_cash_vouchers.id',
                                '=', 'journal_entries.pcv_id')
                            ->where('material_requisition_forms.list_pa_id', '=', $id)
                            ->orWhere('bookstore_requisition_forms.list_pa_id', '=', $id)
                            ->orWhere('other_transactions.list_pa_id', '=', $id)
                            ->orWhere('petty_cash_vouchers.list_pa_id', '=', $id)
                            ->get();

    }
}
