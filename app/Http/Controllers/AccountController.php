<?php

namespace App\Http\Controllers;

use App\AccessedPrimaryAccounts;
use App\AccessedSecondaryAccounts;
use App\AccessedTertiaryAccounts;
use App\Budget;
use App\ListOfPrimaryAccounts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function accessedAccountsView(){
        $primary = AccessedPrimaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Open')->get();
        $secondary = AccessedSecondaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Open')->get();
        $tertiary = AccessedTertiaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Open')->get();
        $pendingPA = AccessedPrimaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Pending')->get();
        $pendingSA = AccessedSecondaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Pending')->get();
        $pendingTA = AccessedTertiaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Pending')->get();

        return view('accessedAccountsView')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary)
            ->with('pendingPA', $pendingPA)
            ->with('pendingSA', $pendingSA)
            ->with('pendingTA', $pendingTA);
    }

    public function requestAccessForm(){
        //TODO Ayusin yung filter... :D
        $latest_budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first(); //can cause problems
        $primary = ListOfPrimaryAccounts::where('budget_id', $latest_budget->id)->get();
        $secondary = new Collection;
        $tertiary = new Collection;

        foreach($primary as $p){
            $secondary = $p->list_of_secondary_accounts;

            foreach($secondary as $s){
                $secondary->push($s);
                $tertiary = $s->list_of_tertiary_accounts;

                foreach($tertiary as $t){
                    $tertiary->push($t);
                }
            }
        }

        $primary->reject(function($p){
            $accessedPA = AccessedPrimaryAccounts::where('user_id', Auth::user()->id)->get();
            foreach($accessedPA as $AP){
                if($p->id == $AP->list_id){
                    return true;
                }
                return false;
            }
        });

        $secondary->reject(function($s){
            $accessedSA = AccessedSecondaryAccounts::where('user_id', Auth::user()->id)->get();
            foreach($accessedSA as $AS){
                if($s->id == $AS->list_id){
                    return true;
                }
                return false;
            }
        });

        $tertiary->reject(function($t){
            $accessedTA = AccessedTertiaryAccounts::where('user_id', Auth::user()->id)->get();
            foreach($accessedTA as $AT){
                if($t->id == $AT->list_id){
                    return true;
                }
                return false;
            }
        });


        //return view form
        return view('requestAccessAccountForm')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary);
    }

    public function requestAccessSave(){

    }
}
