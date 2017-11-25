<?php

namespace App\Http\Controllers;

use App\Budget;
use App\PettyCashVoucher;
use App\PrimaryAccounts;
use Illuminate\Http\Request;

class PettyCashController extends Controller
{
    public function requestPettyCashForm(){
        //TODO get all primary accounts for this year's budget
        $budget = Budget::latest()->first();
        $list = $budget->list_of_primary_accounts;
        return view('requestPettyCash')->with('accounts', $list);
    }

    public function getSubAccounts(Request $request){
        $name = $request->name;
        $account = PrimaryAccounts::where('name', $name)->first();
        return $account;
    }

    public function recordRequestPCV(Request $request){
        $this->validate($request, [
            'purpose' => 'required',
            'amount' => 'required',
            'account' => 'required'
        ]);

        //GET Account ID
        $acc = $request->account;
        

        $pcv = new PettyCashVoucher();
        $pcv->requested_by = Auth::user();
        $pcv->amount = $request->amount;
        $pcv->purpose = $request->purpose;
        $pcv->status = 'Approval';
    }
}
