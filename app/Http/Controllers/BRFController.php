<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookstoreRequisitionForm;
use Illuminate\Support\Facades\Auth;

class BRFController extends Controller
{
    public function brfView(){
        $brfA = BookstoreRequisitionForm::where('id', Auth::user()->id)->get();
        $brfB = BookstoreRequisitionForm::where('id', Auth::user()->id)
                        ->where('amount', null)->get();

        return view('brfView')
            ->with('brfA', $brfA)
            ->with('brfB', $brfB);
    }

    public function brfAdd(){

    }

    public function accessBRF(Request $request){

    }
}
