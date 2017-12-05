<?php

namespace App\Http\Controllers;

use App\BookstoreRequisitionForm;
use App\Budget;
use App\JournalEntries;
use App\ListOfPrimaryAccounts;
use App\ListOfSecondaryAccounts;
use App\ListOfTertiaryAccounts;
use App\MaterialRequisitionFormEntries;
use App\OtherTransactions;
use App\PaymentRequisitionSlips;
use App\PettyCashVoucher;
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

    public function primaryLedger(){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $id = $budget->id;
        $entries = JournalEntries::all();

        //primary accounts to display in choices
        $list_PA = ListOfPrimaryAccounts::where('budget_id', $id)->get();
        $pa_id = $list_PA->first()->id;

        //to receive the balance
        $pa = ListOfPrimaryAccounts::find($pa_id)->first();

        $filtered = $entries->filter(function ($entry) use ($pa_id){
           if( $entry->mrf_entry_id != null ){
               return $entry->mrf_entry->mrf->list_PA->id == $pa_id;
           } else if( $entry->brf_id != null ){
               if( $entry->brf->list_pa_id != null){
                   return $entry->brf->list_PA->id == $pa_id;
               } else if( $entry->brf->list_sa_id != null){
                   return $entry->brf->list_SA->list_of_primary_accounts->id == $pa_id;
               } else if( $entry->brf->list_ta_id != null){
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

        $sorted = $filtered->sortBy('created_at');

        return view('ledger')
            ->with('entries', $sorted)
            ->with('primary', $pa)
            ->with('list', $list_PA);
    }

    public function getLedger(Request $request){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $id = $budget->id;
        $entries = JournalEntries::all();

        $list_PA = ListOfPrimaryAccounts::where('budget_id', $id)->get();

        $type = str_before($request->id, '-');
        $id = (int) str_after($request->id, '-');

        if( $type == 'p'){
            $balance = ListOfPrimaryAccounts::find($id);
            $pa_id = $id;
            $filtered = $entries->filter(function ($entry) use ($pa_id){
                if( $entry->mrf_entry_id != null ){
                    return $entry->mrf_entry->mrf->list_PA->id == $pa_id;
                } else if( $entry->brf_id != null ){
                    if( $entry->brf->list_pa_id != null){
                        return $entry->brf->list_PA->id == $pa_id;
                    } else if( $entry->brf->list_sa_id != null){
                        return $entry->brf->list_SA->list_of_primary_accounts->id == $pa_id;
                    } else if( $entry->brf->list_ta_id != null){
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

            $sorted = $filtered->sortBy('created_at');
            return view('ledger')
                ->with('entries', $sorted) //to show stuff
                ->with('primary', $balance) //balance
                ->with('list', $list_PA); //for filter

        } else if( $type == 's'){
            $balance = ListOfSecondaryAccounts::find($id);
            $sa_id = $id;
            $filtered = $entries->filter(function ($entry) use ($sa_id){
                if( $entry->mrf_entry_id != null ){
                    if( $entry->mrf_entry->list_sa_id != null ){
                        return $entry->mrf_entry->list_sa_id == $sa_id;
                    } else if( $entry->mrf_entry->list_ta_id != null ){
                        return $entry->mrf_entry->list_TA->list_of_secondary_accounts->id == $sa_id;
                    }
                } else if( $entry->brf_id != null ){
                    if( $entry->brf->list_pa_id != null){
                        return false;
                    } else if( $entry->brf->list_sa_id != null){
                        return $entry->brf->list_sa_id == $sa_id;
                    } else if( $entry->brf->list_ta_id != null){
                        return $entry->brf->list_TA->list_of_secondary_accounts->id == $sa_id;
                    }
                } else if( $entry->pcv_id != null ){
                    if( $entry->pcv->list_pa_id != null){
                        return false;
                    } else if( $entry->pcv->list_sa_id != null){
                        return $entry->pcv->list_SA->id == $sa_id;
                    } else if( $entry->pcv->list_ta_id != null){
                        return $entry->pcv->list_TA->list_of_secondary_accounts->id == $sa_id;
                    }
                } else if( $entry->transaction_id != null){
                    if( $entry->otherTransactions->list_pa_id != null){
                        return false;
                    } else if( $entry->otherTransactions->list_sa_id != null){
                        return $entry->otherTransactions->list_SA->id == $sa_id;
                    } else if( $entry->otherTransactions->list_ta_id != null){
                        return $entry->otherTransactions->list_TA->list_of_secondary_accounts->id == $sa_id;
                    }
                }
            });

            $sorted = $filtered->sortBy('created_at');
            return view('ledger')
                ->with('entries', $sorted) //to show stuff
                ->with('secondary', $balance) //balance
                ->with('list', $list_PA); //for filter
        } else {
            $balance = ListOfTertiaryAccounts::find($id);
            $ta_id = $id;
            $filtered = $entries->filter(function ($entry) use ($ta_id){
                if( $entry->mrf_entry_id != null ){
                    if( $entry->mrf_entry->list_sa_id != null ){
                        return false;
                    } else if( $entry->mrf_entry->list_ta_id != null ){
                        return $entry->mrf_entry->list_TA->id == $ta_id;
                    }
                } else if( $entry->brf_id != null ){
                    if( $entry->brf->list_pa_id != null){
                        return false;
                    } else if( $entry->brf->list_sa_id != null){
                        return false;
                    } else if( $entry->brf->list_ta_id != null){
                        return $entry->brf->list_TA->id == $ta_id;
                    }
                } else if( $entry->pcv_id != null ){
                    if( $entry->pcv->list_pa_id != null){
                        return false;
                    } else if( $entry->pcv->list_sa_id != null){
                        return false;
                    } else if( $entry->pcv->list_ta_id != null){
                        return $entry->pcv->list_TA->id == $ta_id;
                    }
                } else if( $entry->transaction_id != null){
                    if( $entry->otherTransactions->list_pa_id != null){
                        return false;
                    } else if( $entry->otherTransactions->list_sa_id != null){
                        return false;
                    } else if( $entry->otherTransactions->list_ta_id != null){
                        return $entry->otherTransactions->list_TA->id == $ta_id;
                    }
                }
            });
            $sorted = $filtered->sortBy('created_at');
            return view('ledger')
                ->with('entries', $sorted) //to show stuff
                ->with('tertiary', $balance) //balance
                ->with('list', $list_PA); //for filter
        }
    }

    public function adjustEntry(Request $request){
        $this->validate($request, [
           'id' => 'required',
           'amount' => 'required'
        ]);

        $type = $request->type;
        $id = $request->id;

        $entry = new JournalEntries;

        if( $type == 'mrf'){
            $entry->mrf_entry_id = $id;

            $current = $entry->mrf_entry->quantity * $entry->mrf_entry->unit_price;
            $entry->amount = $current - $request->amount;

        } else if( $type == 'brf'){
            $entry->brf_id = $id;

            $current = 0;
            foreach($entry->brf->entries as $b){
                $current += $b->amount;
            }

            $entry->amount = $current - $request->amount;
        } else if( $type == 'pcv'){
            $entry->pcv_id = $id;

            $current = $entry->pcv->amount - $entry->pcv->amount_received;
            $entry->amount = $current - $request->amount;
        } else {
            $entry->transaction_id = $id;

            $current = $entry->otherTransactions->amount;
            $entry->amount = $current - $request->amount;
        }

        $entry->adjust = true;
        $entry->save();

        return redirect()->route('disbursementJournal');
    }

    public function adjustForm(Request $request){
        $type = str_before($request->id, '-');
        $id = (int) str_after($request->id, '-');

        if( $type == 'mrf'){
            $mrf = MaterialRequisitionFormEntries::find($id);

            return view('adjustForm')
                ->with('id', $id)
                ->with('type', $type)
                ->with('mrf', $mrf);
        } else if( $type == 'brf'){
            $brf = BookstoreRequisitionForm::find($id);

            return view('adjustForm')
                ->with('id', $id)
                ->with('type', $type)
                ->with('brf', $brf);
        } else if( $type == 'pcv'){
            $pcv = PettyCashVoucher::find($id);

            return view('adjustForm')
                ->with('id', $id)
                ->with('type', $type)
                ->with('pcv', $pcv);
        } else {
            $transac = OtherTransactions::find($id);

            return view('adjustForm')
                ->with('id', $id)
                ->with('type', $type)
                ->with('transac', $transac);
        }
    }
}
