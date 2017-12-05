<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;

class ReportsController extends Controller
{
    //users that accessed the system
    public function getListOfUsers(){
        $users = DB::table('users')
                    ->join('petty_cash_vouchers', 'petty_cash_vouchers.received_by', '=',
                        'users.id')
                    ->join('accessed_primary_accounts', 'accessed_primary_accounts.user_id',
                        '=', 'users.id')
                    ->where('users.id', '!=', Auth::id())
                    ->get();

        echo($users);
    }

}
