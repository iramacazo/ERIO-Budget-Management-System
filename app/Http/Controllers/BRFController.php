<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookstoreRequisitionForm;

class BRFController extends Controller
{
    public function brfView(){
        $brf = BookstoreRequisitionForm::where('id', Auth::user()->id)->get();

        return view('brfView');
    }
}
