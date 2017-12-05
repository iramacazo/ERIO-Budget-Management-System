<?php

namespace App\Http\Controllers;

use App\JournalEntries;
use App\ListOfPrimaryAccounts;
use App\ListOfSecondaryAccounts;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Budget;
use Carbon\Carbon;

class ReportsController extends Controller
{
    //users that accessed the system
    public function getListOfUsers(){
        $users = DB::table('users')
                    ->join('petty_cash_vouchers', 'petty_cash_vouchers.received_by', '=',
                        'users.id')
                    ->join('accessed_primary_accounts', 'accessed_primary_accounts.user_id',
                        '=', 'users.id')
                    ->where('users.id', '!=', Auth::id())
                    ->get();

        echo($users);
    }

    public function accountsActivityPA(){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $id = $budget->id;
        $pa = ListOfPrimaryAccounts::where('budget_id', $id)->get();
        $entries = JournalEntries::all();

        $list = new Collection();
        foreach($pa as $p){
            $pa_id = $p->id;

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

            $total = 0; //total expenses
            foreach($filtered as $f){
                if( $f->adjust == false ){
                    if( $f->mrf_entry_id != null ){
                        $total += $f->mrf_entry->unit_price * $f->mrf_entry->quantity;
                    } else if( $f->brf_id != null ){
                        $brfTotal = 0;
                        foreach($f->brf->entries as $b){
                            $brfTotal += $b->amount;
                        }
                        $total += $brfTotal;
                    } else if( $f->pcv_id != null ){
                        $total += $f->pcv->amount;
                    } else if( $f->transaction_id != null){
                        $total += $f->otherTransactions->amount;
                    }
                } else {
                    if( $f->amount < 0){
                        $total += $f->amount * -1; //had to pay more so should add to expense
                    } else {
                        $total -= $f->amount; //means na-over-record kayo thus bawas dapat sa expense
                    }
                }
            }

            $tempo = collect([
               'name' => $p->primary_accounts->name,
               'expense' => $total,
               'budget' => $p->amount
            ]);

            $list->push($tempo);
        }

        $sorted = $list->sortByDesc('expense');

        return view('accountsActivity')
            ->with('accounts', $sorted)
            ->with('type', 'Primary');
    }

    public function accountsActivitySA(){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
        $id = $budget->id;
        $sa = ListOfSecondaryAccounts::all();
        $entries = JournalEntries::all();

        $rightSA = $sa->filter(function($s) use ($id){
           return $s->list_of_primary_accounts->budget_id == $id;
        });

        $list = new Collection();

        foreach($rightSA as $s){
            $sa_id = $s->id;

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

            $total = 0; //total expenses
            foreach($filtered as $f){
                if( $f->adjust == false ){
                    if( $f->mrf_entry_id != null ){
                        $total += $f->mrf_entry->unit_price * $f->mrf_entry->quantity;
                    } else if( $f->brf_id != null ){
                        $brfTotal = 0;
                        foreach($f->brf->entries as $b){
                            $brfTotal += $b->amount;
                        }
                        $total += $brfTotal;
                    } else if( $f->pcv_id != null ){
                        $total += $f->pcv->amount;
                    } else if( $f->transaction_id != null){
                        $total += $f->otherTransactions->amount;
                    }
                } else {
                    if( $f->amount < 0){
                        $total += $f->amount * -1; //had to pay more so should add to expense
                    } else {
                        $total -= $f->amount; //means na-over-record kayo thus bawas dapat sa expense
                    }
                }
            }

            $name = $s->secondary_accounts->name . ': ' . $s->secondary_accounts->primary_accounts->name;
            $tempo = collect([
                'name' => $name,
                'expense' => $total,
                'budget' => $s->amount
            ]);

            $list->push($tempo);
        }

        $sorted = $list->sortByDesc('expense');

        return view('accountsActivity')
            ->with('accounts', $sorted)
            ->with('type', 'Secondary');
    }
}
