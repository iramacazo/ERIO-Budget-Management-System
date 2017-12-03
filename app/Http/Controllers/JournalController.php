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

    public function journalPrimary($primary = 'null'){
        if($primary == null){
            $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
            $id = ListOfPrimaryAccounts::where('budget_id', $budget->id)->first()->id;

            $entries = JournalEntries::all();

            $entries->reject(function ($entry) use ($id){
                if($entry->mrf_entry_id != null){
                    return $entry->mrf_entry->mrf->list_pa_id != $id;
                } else if($entry->brf_id != null){
                    return $entry->brf->list_pa_id == null || $entry->brf->list_pa_id != $id;
                } else if($entry->pcv_id != null){
                    return $entry->pcv->list_pa_id == null || $entry->pcv->list_pa_id != $id;
                } else {
                    return $entry->otherTransactions->list_pa_id == null || $entry->pcv->list_pa_id != $id;
                }
            });

            return view('generalJournalView')->with('entries', $entries);

//
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
}
