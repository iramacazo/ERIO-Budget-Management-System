<?php

namespace App\Http\Controllers;

use App\BookstoreRequisitionFormEntries;
use App\JournalEntries;
use Illuminate\Http\Request;
use App\BookstoreRequisitionForm;
use Illuminate\Support\Facades\Auth;
use App\Budget;
use Carbon\Carbon;
use DB;

class BRFController extends Controller
{
    public function brfView(Request $request){
        //where entries already have amount
        $brfA = BookstoreRequisitionForm::where('user_id', Auth::user()->id)
                                        ->where('status', 'Billed')->get();

        //where entries doesn't have amount
        $brfB = BookstoreRequisitionForm::where('user_id', Auth::user()->id)
                                        ->where('status', 'Pending')->get();

        return view('brfView')
            ->with('brfA', $brfA)
            ->with('brfB', $brfB);
    }

    public function brfAdd(Request $request){
        $latest_budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first(); //can cause problems

        $primary = DB::table('list_of_primary_accounts')
                        ->join('accessed_primary_accounts', 'accessed_primary_accounts.list_id',
                            '=', 'list_of_primary_accounts.id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'list_of_primary_accounts.account_id')
                        ->select(
                            'list_of_primary_accounts.id as id',
                            'primary_accounts.name as pa_name '
                        )
                        ->where('primary_accounts.name', 'like', '%upplies%')
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
                        ->where('secondary_accounts.name', 'like', '%upplies%')
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
                        ->where('tertiary_accounts.name', 'like', '%upplies%')
                        ->where('accessed_tertiary_accounts.user_id', Auth::user()->id)
                        ->get();

        return view('addBRF')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary);
    }

    public function saveBRF(Request $request){
        $type = str_before($request->account, '-');
        $id = str_after($request->account, '-');

        $brf = new BookstoreRequisitionForm();
        if($type == 'p'){
            $brf->list_pa_id = $id;
        } else if($type == 's'){
            $brf->list_sa_id = $id;
        } else {
            $brf->list_ta_id = $id;
        }
        $brf->user_id = Auth::user()->id;
        $brf->save();

        $brf_id = BookstoreRequisitionForm::latest()->first();

        $total = 0;
        foreach($request->desc as $d){
            $total++;
        }

        for($i = 0; $i < $total; $i++){
            $entry = new BookstoreRequisitionFormEntries();
            $entry->brf_id = $brf_id->id;
            $entry->quantity = $request->qty[$i];
            $entry->description = $request->desc[$i];
            $entry->save();
        }

        return redirect()->route('brfView');

    }

    public function accessBRF(Request $request){
        if($request->submit == 'Retrieve Amounts'){
            $brf = BookstoreRequisitionForm::find($request->id);
            return view('retrieveAmounts')->with('brf', $brf);
        } else {
            return redirect()->route('brfView'); //print
        }
    }

    public function printBRF(Request $request){
        $brf = BookstoreRequisitionForm::find($request->id);
        return view('printBRF')->with('brf', $brf);
    }
    
    public function saveAmount(Request $request){
        $total = 0;
        foreach($request->id as $id){
            $total++;
        }

        for($i = 0; $i < $total; $i++){
            $brfEntry = BookstoreRequisitionFormEntries::find($request->id[$i]);
            $brfEntry->amount = $request->amount[$i];
            $brf = BookstoreRequisitionForm::find($brfEntry->brf_id);
            $brfEntry->save();
            $brf->status = 'Billed';
            $brf->save();
        }

        $entry = new JournalEntries();
        $entry->brf_id = BookstoreRequisitionForm::latest('updated_at')->first()->id;
        $entry->save();

        return redirect()->route('brfView');
    }

    public function testResults(Request $request){

        //insert logic :D
        return view('test', ['sent' => $request->desc]);
    }
}
