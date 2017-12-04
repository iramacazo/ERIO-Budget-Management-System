<?php

namespace App\Http\Controllers;

use App\Budget;
use App\JournalEntries;
use App\ListOfPrimaryAccounts;
use App\ListOfSecondaryAccounts;
use App\ListOfTertiaryAccounts;
use App\PaymentRequisitionSlips;
use App\PettyCashVoucher;
use App\PrimaryAccounts;
use App\SecondaryAccounts;
use App\TertiaryAccounts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PettyCashController extends Controller
{
    public function requestPettyCashForm(){
        $budget = Budget::latest()->whereDate('start_range', '<', Carbon::now())->first();
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

            return view("pettyCashBudgetAdmin")
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

    public function cancelPettyCashRequest(Request $request){
        $this->validate($request, [
           'id' => 'required'
        ]);

        $pettyCash = PettyCashVoucher::find($request->id);
        $pettyCash->status = 'Canceled';
        $pettyCash->save();

        return redirect()->route('pettyCashView');
    }

    public function approvePettyCashRequest(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);

        $pettyCash = PettyCashVoucher::find($request->id);
        $pettyCash->status = 'Receive';
        $pettyCash->save();

        return redirect()->route('pettyCashView');
    }

    public function receivePettyCashForm(Request $request){
        return view('receivePettyCashForm')->with('id', $request->id);
    }

    public function receivePettyCash(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);

        $pettyCash = PettyCashVoucher::find($request->id);
        $pettyCash->status = 'Refill';
        $pettyCash->date_returned = Carbon::now();
        $pettyCash->received_by = Auth::user()->id;
        $pettyCash->amount_received = $request->amount_received;
        $pettyCash->save();

        $entry = new JournalEntries();
        $entry->pcv_id = $pettyCash->id;
        $entry->save();

        return redirect()->route('pettyCashView');
    }

    public function denyPettyCash(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);

        $pettyCash = PettyCashVoucher::find($request->id);
        $pettyCash->status = 'Denied';
        $pettyCash->save();

        return redirect()->route('pettyCashView');
    }

    public function pcrf(Request $request){
        $this->validate($request, [
            'code' => 'required'
        ]);
        $refill = PettyCashVoucher::where('status', 'Refill')->get();

        //TODO create new PCRF

        //create PRS for this...
        $prs = new PaymentRequisitionSlips;
        $prs->code = $request->code;
        $prs->save();

        foreach($refill as $r){
            $entry = $r->journal_entries->first();
            $entry->prs_id = $prs->id;
            $entry->save();

            $r->status = 'Complete';
            $r->save();
        }

        return view('pcrf');

    }
}
