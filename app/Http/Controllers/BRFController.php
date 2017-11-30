<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookstoreRequisitionForm;
use Illuminate\Support\Facades\Auth;
use DB;

class BRFController extends Controller
{
    public function brfView(){
        //where entries already have amount
        $brfA = BookstoreRequisitionForm::where('user_id', Auth::user()->id)
                                        ->where('status', 'Billed')->get();

        //where entries doesnt have amount
        $brfB = BookstoreRequisitionForm::where('user_id', Auth::user()->id)
                                        ->where('status', 'Pending')->get();

        return view('brfView')
            ->with('brfA', $brfA)
            ->with('brfB', $brfB);
    }

    public function brfAdd(){

    }

    public function accessBRF(Request $request){

    }
}
