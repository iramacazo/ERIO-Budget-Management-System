<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Budget;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class MRFController extends Controller
{
    public function viewMRF(){
        return view('mrfView');
    }

    public function addMRFView(){
        $latest_budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first(); //can cause problems

        //TODO implement latest_budget in queries

        $primary = DB::table('list_of_primary_accounts')
                        ->join('accessed_primary_accounts', 'accessed_primary_accounts.list_id',
                            '=', 'list_of_primary_accounts.id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'list_of_primary_accounts.account_id')
                        ->select(
                            'list_of_primary_accounts.id as id',
                            'primary_accounts.name as pa_name'
                        )
                        ->where('primary_accounts.name', 'not like', '%upplies%')
                        ->where('accessed_primary_accounts.user_id', Auth::user()->id)
                        ->get();

        $secondary = DB::table('list_of_secondary_accounts')
                        ->join('accessed_secondary_accounts', 'accessed_secondary_accounts.list_id',
                            '=', 'list_of_secondary_accounts.id')
                        ->join('secondary_accounts', 'secondary_accounts.id', '=',
                            'list_of_secondary_accounts.account_id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'secondary_accounts.account_id')
                        ->select(
                            'list_of_secondary_accounts.id as id',
                            'secondary_accounts.name as sa_name',
                            'primary_accounts.name as pa_name'
                        )
                        ->where('secondary_accounts.name', 'not like', '%upplies%')
                        ->where('accessed_secondary_accounts.user_id', Auth::user()->id)
                        ->get();

        $tertiary = DB::table('list_of_tertiary_accounts')
                        ->join('accessed_tertiary_accounts', 'accessed_tertiary_accounts.list_id',
                            '=', 'list_of_tertiary_accounts.id')
                        ->join('tertiary_accounts', 'tertiary_accounts.id',
                            '=', 'list_of_tertiary_accounts.account_id')
                        ->join('secondary_accounts', 'secondary_accounts.id',
                            '=', 'tertiary_accounts.subaccount_id')
                        ->join('primary_accounts', 'primary_accounts.id',
                            '=', 'secondary_accounts.account_id')
                        ->select(
                            'list_of_tertiary_accounts.id as id',
                            'tertiary_accounts.name as ta_name',
                            'secondary_accounts.name as sa_name',
                            'primary_accounts.name as pa_name'
                        )
                        ->where('tertiary_accounts.name', 'not like', '%upplies%')
                        ->where('accessed_tertiary_accounts.user_id', Auth::user()->id)
                        ->get();

        return view('addMRFView')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary);
    }

    public function ajaxAddEntry()
    {
        $primary = DB::table('list_of_primary_accounts')
            ->join('accessed_primary_accounts', 'accessed_primary_accounts.list_id',
                '=', 'list_of_primary_accounts.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->select(
                'list_of_primary_accounts.id as id',
                'primary_accounts.name as pa_name'
            )
            ->where('primary_accounts.name', 'not like', '%upplies%')
            ->where('accessed_primary_accounts.user_id', Auth::user()->id)
            ->get();

        $secondary = DB::table('list_of_secondary_accounts')
            ->join('accessed_secondary_accounts', 'accessed_secondary_accounts.list_id',
                '=', 'list_of_secondary_accounts.id')
            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                'list_of_secondary_accounts.account_id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'secondary_accounts.account_id')
            ->select(
                'list_of_secondary_accounts.id as id',
                'secondary_accounts.name as sa_name',
                'primary_accounts.name as pa_name'
            )
            ->where('secondary_accounts.name', 'not like', '%upplies%')
            ->where('accessed_secondary_accounts.user_id', Auth::user()->id)
            ->get();

        $tertiary = DB::table('list_of_tertiary_accounts')
            ->join('accessed_tertiary_accounts', 'accessed_tertiary_accounts.list_id',
                '=', 'list_of_tertiary_accounts.id')
            ->join('tertiary_accounts', 'tertiary_accounts.id',
                '=', 'list_of_tertiary_accounts.account_id')
            ->join('secondary_accounts', 'secondary_accounts.id',
                '=', 'tertiary_accounts.subaccount_id')
            ->join('primary_accounts', 'primary_accounts.id',
                '=', 'secondary_accounts.account_id')
            ->select(
                'list_of_tertiary_accounts.id as id',
                'tertiary_accounts.name as ta_name',
                'secondary_accounts.name as sa_name',
                'primary_accounts.name as pa_name'
            )
            ->where('tertiary_accounts.name', 'not like', '%upplies%')
            ->where('accessed_tertiary_accounts.user_id', Auth::user()->id)
            ->get();

        return response()->json([
            'primary' => $primary,
            'secondary' => $secondary,
            'tertiary' => $tertiary
        ]);
    }

    public function saveMRF(Request $request){
        return redirect()->route('viewMRF');
    }
}
