<?php

namespace App\Http\Controllers;

use App\JournalEntries;
use App\OtherTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function transacView(){
        return view('transactionsView');
    }

    public function addTransaction(){
        return view('addTransaction');
    }

    public function saveTransaction(Request $request){
        $this->validate($request, [
           'description' => 'required',
           'amount' => 'required',
           'account' => 'required'
        ]);

        $transaction = new OtherTransactions;
        $transaction->description = $request->description;
        $transaction->amount = $request->amount;
        $transaction->user_id = Auth::user()->id;

        $type = str_before($request->account, '-');
        $id = (int) str_after($request->account, '-');

        if($type == 'p'){
            $transaction->list_pa_id = $id;
        } else if($type == 's'){
            $transaction->list_sa_id = $id;
        } else {
            $transaction->list_ta_id = $id;
        }

        $transaction->save();

        $entry = new JournalEntries();
        $entry->transaction_id = $transaction->id;
        $entry->save();

        return redirect()->route('transacView')->with('recently_added', $request->description);
    }
}
