<?php

namespace App\Http\Controllers;

use App\JournalEntries;
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

        return view('mrfView')
            ->with('pending', $pending)
            ->with('procure', $procure)
            ->with('complete', $complete);
    }

    public function addMRFView(){
        $latest_budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first(); //can cause problems

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
                        ->where('list_of_primary_accounts.budget_id', $latest_budget->id)
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
                        ->join('list_of_primary_accounts', 'list_of_primary_accounts.id',
                            '=', 'list_of_secondary_accounts.list_id')
                        ->select(
                            'list_of_secondary_accounts.id as id',
                            'secondary_accounts.name as sa_name',
                            'primary_accounts.name as pa_name'
                        )
                        ->where('secondary_accounts.name', 'not like', '%upplies%')
                        ->where('list_of_primary_accounts.id', $primary->first()->id)
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
                        ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.id',
                            '=', 'list_of_tertiary_accounts.list_id')
                        ->join('list_of_primary_accounts', 'list_of_primary_accounts.id',
                            '=', 'list_of_secondary_accounts.list_id')
                        ->select(
                            'list_of_tertiary_accounts.id as id',
                            'tertiary_accounts.name as ta_name',
                            'secondary_accounts.name as sa_name',
                            'primary_accounts.name as pa_name'
                        )
                        ->where('primary_accounts.id', $primary->first()->id)
                        ->where('tertiary_accounts.name', 'not like', '%upplies%')
                        ->where('accessed_tertiary_accounts.user_id', Auth::user()->id)
                        ->get();


        return view('addMRFView')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary);
    }

    public function ajaxAddEntry(Request $request)
    {
        $id = str_after($request->pa_id, '-');

        $secondary = DB::table('list_of_secondary_accounts')
            ->join('accessed_secondary_accounts', 'accessed_secondary_accounts.list_id',
                '=', 'list_of_secondary_accounts.id')
            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                'list_of_secondary_accounts.account_id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'secondary_accounts.account_id')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.id',
                '=', 'list_of_secondary_accounts.list_id')
            ->select(
                'list_of_secondary_accounts.id as id',
                'secondary_accounts.name as sa_name',
                'primary_accounts.name as pa_name'
            )
            ->where('secondary_accounts.name', 'not like', '%upplies%')
            ->where('list_of_primary_accounts.id', $id)
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
            ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.id',
                '=', 'list_of_tertiary_accounts.list_id')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.id',
                '=', 'list_of_secondary_accounts.list_id')
            ->select(
                'list_of_tertiary_accounts.id as id',
                'tertiary_accounts.name as ta_name',
                'secondary_accounts.name as sa_name',
                'primary_accounts.name as pa_name'
            )
            ->where('primary_accounts.id', $id)
            ->where('tertiary_accounts.name', 'not like', '%upplies%')
            ->where('accessed_tertiary_accounts.user_id', Auth::user()->id)
            ->get();

        return response()->json([
            'secondary' => $secondary,
            'tertiary' => $tertiary
        ]);
    }

    public function saveMRF(Request $request){
        $total = 0;
        foreach($request->desc as $d){
            $total++;
        }

        $pa_id = (int) str_after($request->primary_account, "-");

        $mrf = new MaterialRequisitionForm();
        $mrf->form_num = $request->form_num;
        $mrf->date_needed = $request->date_needed;
        $mrf->place_of_delivery = $request->place_of_delivery;
        $mrf->requested_by = Auth::user()->id;
        $mrf->contact_person = $request->contact_person;
        $mrf->contact_person_email = $request->contact_person_email;
        $mrf->list_pa_id = $pa_id;
        $mrf->dept = 'VPERI'; //katamad tangalin sa code
        $mrf->save();

        $latest_mrf = MaterialRequisitionForm::latest()->first();

        for($i = 0; $i < $total; $i++){
            $mrfEntry = new MaterialRequisitionFormEntries();
            $mrfEntry->quantity = $request->qty[$i];
            $mrfEntry->description = $request->desc[$i];

            $entryAcc = $request->acc[$i];
            $type = str_before($entryAcc, '-');
            $acc =  str_after($entryAcc, '-');
            $id = (int) $acc;

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

    public function execMRFView(){
        $pending = MaterialRequisitionForm::where('status', 'Pending')->get();

        return view('execMRFView')
            ->with('pending', $pending);
    }

    public function approveMRF(Request $request){
        $mrf = MaterialRequisitionForm::find($request->id);
        $mrf->status = 'Procure';
        $mrf->save();

        return redirect()->route('execMRF');
    }

    public function printMRF(Request $request){
        $mrf = MaterialRequisitionForm::find($request->id);

        return view('printMRF')->with('mrf', $mrf);
    }

    public function receiveAmounts(Request $request){
        $mrf = MaterialRequisitionForm::where('id', $request->id)->first();

        return view('receiveAmountsMRF')
            ->with('mrf', $mrf);
    }

    public function saveAmounts(Request $request){
        $this->validate($request, [
           'mrf_id' => 'required'
        ]);

        $mrf = MaterialRequisitionForm::find($request->mrf_id);

        $ctr = 0;
        $entries = $mrf->entries;
        foreach($entries as $entry){
            $entry->unit_price = $request->unit_price[$ctr];
            $entry->supplies = $request->supplier[$ctr];
            $entry->save();

            $journal_entry = new JournalEntries();
            $journal_entry->mrf_entry_id = $entry->id;
            $journal_entry->save();
            $ctr++;
        }

        $mrf->status = 'Complete';
        $mrf->save();


        return redirect()->route('viewMRF');
    }
}
