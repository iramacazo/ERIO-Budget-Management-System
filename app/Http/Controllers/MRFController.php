<?php

namespace App\Http\Controllers;

use App\MaterialRequisitionForm;
use App\MaterialRequisitionFormEntries;
use Illuminate\Http\Request;
use App\Budget;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class MRFController extends Controller
{
    public function viewMRF(){
        $pending = MaterialRequisitionForm::where('status', 'Pending')
                    ->where('requested_by', Auth::user()->id)->get();
        $procure = MaterialRequisitionForm::where('status', 'Procure')
                    ->where('requested_by', Auth::user()->id)->get();
        $complete = MaterialRequisitionForm::where('status', 'Complete')
                    ->where('requested_by', Auth::user()->id)->get();

        return view('mrfView');
    }

    public function addMRFView(){
        $latest_budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first(); //can cause problems

        //TODO implement latest_budget in queries
        //TODO only get primary accounts then ajax the secondary&tertiary accounts of selected PA

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
        //TODO get entries of chosen primary account
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
        $total = 0;
        foreach($request->desc as $d){
            $total++;
        }

        $mrf = new MaterialRequisitionForm();
        $mrf->form_num = $request->form_num;
        $mrf->date_needed = $request->date_needed;
        $mrf->place_of_delivery = $request->place_of_delivery;
        $mrf->dept = 'VPERI'; //katamad tangalin sa code
        $mrf->save();

        $latest_mrf = MaterialRequisitionForm::latest()->first();

        for($i = 0; $i < $total; $i++){
            $mrfEntry = new MaterialRequisitionFormEntries();
            $mrfEntry->quantity = $request->qty[$i];
            $mrfEntry->description = $request->desc[$i];

            $entryAcc = $request->acc[$i];
            $type = str_before($entryAcc, '-');
            $id = str_after($entryAcc, '-');

            if($type == 's'){
                $mrfEntry->list_sa_id = $id;
            } else {
                $mrfEntry->list_ta_id = $id;
            }

            $mrfEntry->mrf_id = $latest_mrf->id;
            $mrfEntry->save();
        }

        return redirect()->route('viewMRF');
    }
}
