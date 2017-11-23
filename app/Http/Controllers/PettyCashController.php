<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PettyCashController extends Controller
{
    public function requestPettyCashForm(){
        //TODO get all primary accounts for this year's budget

        return view('requestPettyCash');
    }
}
