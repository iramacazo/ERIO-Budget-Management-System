<?php

namespace App\Http\Controllers;

use App\BookstoreRequisitionForm;
use App\JournalEntries;
use App\MaterialRequisitionFormEntries;
use App\OtherTransactions;
use App\PaymentRequisitionSlips;
use Illuminate\Http\Request;

class PRSController extends Controller
{
    public function savePRS(Request $request){
        $this->validate($request, [
            'code' => 'required|unique:payment_requisition_slips,code'
        ]);

        $prs = new PaymentRequisitionSlips;
        $prs->code = $request->code;
        $prs->save();

        $entry = JournalEntries::find($request->id);
        $entry->prs_id = $prs->id;
        $entry->save();

        return view('prsPrint')->with('prs', $entry);
    }

    public function prsForm(Request $request){
        return view('prsForm')->with('id', $request->id);
    }
}
