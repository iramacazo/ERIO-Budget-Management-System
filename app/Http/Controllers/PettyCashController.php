<?php

namespace App\Http\Controllers;

use App\Budget;
use App\ListOfPrimaryAccounts;
use App\ListOfSecondaryAccounts;
use App\ListOfTertiaryAccounts;
use App\PettyCashVoucher;
use App\PrimaryAccounts;
use App\SecondaryAccounts;
use App\TertiaryAccounts;
use Illuminate\Support\Facades\Auth;
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
        $type = str_before('-', $request->account);
        $acc = str_after('-', $request->account);
        $id = intval($acc, 8); //TODO ETO ATA PROBLEMA :D

        $pcv = new PettyCashVoucher();
        $pcv->requested_by = Auth::user();
        $pcv->amount = $request->amount;
        $pcv->purpose = $request->purpose;
        $pcv->status = 'Approval';

        //TODO fix this
        if($type == 'p'){
            $pcv->list_pa_id = $id;
        } else if($type == 's'){
            $pcv->list_sa_id = $id;
        } else {
            $pcv->list_ta_id = $id;
        }

        $pcv->save();

        return view('requestPettyCashResult')->with("result", $pcv);

    }
}
