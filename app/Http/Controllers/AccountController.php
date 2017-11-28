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
use DB;

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

        $primary = DB::table('list_of_primary_accounts')
                        ->leftJoin('accessed_primary_accounts', 'list_of_primary_accounts.id', '=',
                            'accessed_primary_accounts.list_id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'list_of_primary_accounts.account_id')
                        ->select(
                            'primary_accounts.name as primary_name',
                            'list_of_primary_accounts.id as id'
                        )
                        ->where('list_of_primary_accounts.budget_id', $latest_budget->id)
                        ->whereNull('accessed_primary_accounts.id')
                        ->get();

        $secondary = DB::table('list_of_secondary_accounts')
                        ->leftJoin('accessed_secondary_accounts', 'list_of_secondary_accounts.id',
                            '=', 'accessed_secondary_accounts.list_id')
                        ->join('list_of_primary_accounts', 'list_of_primary_accounts.id', '=',
                            'list_of_secondary_accounts.list_id')
                        ->join('secondary_accounts', 'secondary_accounts.id', '=',
                            'list_of_secondary_accounts.account_id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'secondary_accounts.account_id')
                        ->select(
                            'secondary_accounts.name as secondary_name',
                            'list_of_secondary_accounts.id as id',
                            'primary_accounts.name as primary_name'
                        )
                        ->where('list_of_primary_accounts.budget_id', $latest_budget->id)
                        ->whereNull('accessed_secondary_accounts.id')
                        ->get();

        $tertiary = DB::table('list_of_tertiary_accounts')
                        ->leftJoin('accessed_tertiary_accounts', 'list_of_tertiary_accounts.id', '=',
                            'accessed_tertiary_accounts.list_id')
                        ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.id', '=',
                            'list_of_tertiary_accounts.list_id')
                        ->join('list_of_primary_accounts', 'list_of_primary_accounts.id', '=',
                            'list_of_secondary_accounts.list_id')
                        ->join('tertiary_accounts', 'list_of_tertiary_accounts.account_id', '=',
                            'tertiary_accounts.id')
                        ->join('secondary_accounts', 'secondary_accounts.id', '=',
                            'tertiary_accounts.subaccount_id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'secondary_accounts.account_id')
                        ->select(
                            'tertiary_accounts.name as tertiary_name',
                            'list_of_tertiary_accounts.id as id',
                            'secondary_accounts.name as secondary_name',
                            'primary_accounts.name as primary_name'
                        )
                        ->where('list_of_primary_accounts.budget_id', $latest_budget->id)
                        ->whereNull('accessed_tertiary_accounts.id')
                        ->get();


        //return view form
        return view('requestAccessAccountForm')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary);
    }

    public function requestAccessSave(Request $request){
        $this->validate($request, [
            'explanation' => 'required',
            'account' => 'required'
        ]);

        $type = str_before($request->account, '-');
        $acc = str_after($request->account, '-');
        $id = (int) $acc;

        if($type == 'p'){
            $access = new AccessedPrimaryAccounts();
            $access->explanation = $request->explanation;
            $access->user_id = Auth::user()->id;
            $access->list_id = $id;
            $access->save();
        } else if($type == 's'){
            $access = new AccessedSecondaryAccounts();
            $access->explanation = $request->explanation;
            $access->user_id = Auth::user()->id;
            $access->list_id = $id;
            $access->save();
        } else {
            $access = new AccessedTertiaryAccounts();
            $access->explanation = $request->explanation;
            $access->user_id = Auth::user()->id;
            $access->list_id = $id;
            $access->save();
        }

        return route('accessedAccountsView');
    }
}
