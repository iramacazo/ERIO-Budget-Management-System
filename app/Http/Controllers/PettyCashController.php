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

    public function pettyCashView(){

        if(Auth::user()->usertype == "Budget Requestee"){
            $pending = PettyCashVoucher::where('status', 'Approval')
                                        ->where('requested_by', Auth::user()->id)->get();
            $receiving = PettyCashVoucher::where('status', 'Receive')
                                        ->where('requested_by', Auth::user()->id)->get();
            $br_completed = PettyCashVoucher::where('status', '!=', 'Approval')
                                        ->where('status', '!=', 'Receive')
                                        ->where('requested_by', Auth::user()->id)->get();

            return view("pettyCash")
                ->with('pending', $pending)
                ->with('receiving', $receiving)
                ->with('br_completed', $br_completed);

        } else {
            $pending = PettyCashVoucher::where('status', 'Approval')->get();
            $receiving = PettyCashVoucher::where('status', 'Receive')->get();
            $refill = PettyCashVoucher::where('status', 'Refill')->get();
            $completed = PettyCashVoucher::where('status', 'Complete')->get();

            return view("pettyCash")
                ->with('pending', $pending)
                ->with('receiving', $receiving)
                ->with('refill', $refill)
                ->with('completed', $completed);
        }
    }

    public function recordRequestPCV(Request $request){
        $this->validate($request, [
            'purpose' => 'required',
            'amount' => 'required',
            'account' => 'required'
        ]);

        //GET Account ID
        $type = str_before($request->account, '-');
        $acc = str_after($request->account, '-');
        $id = (int) $acc; //TODO ETO ATA PROBLEMA :D

        $pcv = new PettyCashVoucher();
        $pcv->requested_by = Auth::user()->id;
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
