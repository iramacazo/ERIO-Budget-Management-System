<?php

namespace App\Http\Controllers;

use App\AccessedPrimaryAccounts;
use App\AccessedSecondaryAccounts;
use App\AccessedTertiaryAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function accessedAccountsView(){
        $primary = AccessedPrimaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Open')->get();
        $secondary = AccessedSecondaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Open')->get();
        $tertiary = AccessedTertiaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Open')->get();
        $pendingPA = AccessedPrimaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Pending')->get();
        $pendingSA = AccessedSecondaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Pending')->get();
        $pendingTA = AccessedTertiaryAccounts::where('user_id', Auth::user()->id)
                                            ->where('status', 'Pending')->get();

        return view('accessedAccountsView')
            ->with('primary', $primary)
            ->with('secondary', $secondary)
            ->with('tertiary', $tertiary)
            ->with('pendingPA', $pendingPA)
            ->with('pendingSA', $pendingSA)
            ->with('pendingTA', $pendingTA);
    }
}
