<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::view('/invalid-user', 'auth.user_permission')->name('invalid_user');

Route::get('/', function(){
    if(Auth::guest()) {
        return view('homepage');
    }else if (Auth::user()->status == "inactive"){
        return redirect()->route('invalid_user');
    }else if(Auth::user()->usertype == "System Admin"){
        return redirect("/all-users");
    }else if(Auth::user()->usertype == "Executive"){
        return redirect("/request-accounts");
    }else if(Auth::user()->usertype == "Budget Requestee"){
        return redirect('/accounts');
    }else if(Auth::user()->usertype == "Budget Admin"){
        return redirect()->route('budget_dash');
    }else{
        return view('homepage');
    }
})->name('homepage');

Auth::routes();

Route::get('/edit_account', 'AdminController@editAccount')->name('edit_account');

Route::post('/edit_account/submit', 'AdminController@saveChangesToAccount')->name('save_account');

Route::view('/unauthorized_access','unauthorizedAccess')->name('unauthorized_access');

/* System Admin Routes */
Route::middleware(['system_admin'])->group(function (){
    Route::get('/all-users', 'AdminController@getAllUsers')->name('get-all-users');

    Route::view('/add_user', 'addUsers')->name('add_user');

    Route::get('/edit_user/{user}', function(\App\User $user){
        return view("editAccountAdmin", compact('user'));
    })->name('edit-user');

    Route::post('/edit_user/{user}/save', 'AdminController@saveOtherAccount')
        ->name('save-other-user');

    Route::get('/deactivate_user/{user}', function(\App\User $user){
        $user->status = 'inactive';
        $user->last_deactivated = \Carbon\Carbon::now();
        $user->save();
        return back()->with('deactivate', $user->email);
    })->name('deactivate-user');

    Route::get('/activate_user/{user}', function(\App\User $user){
        $user->status = 'active';
        $user->save();
        return back()->with('active', $user->email);
    })->name('reactivate-user');

    Route::post('/add_user/submit', 'AdminController@createUser')->name('create_user');
});

Route::middleware(['budget_admin'])->group(function(){
    Route::get('/budget', 'BudgetController@budgetDashboard')->name('budget_dash');

    Route::post('/budget/add-account/{id}', 'BudgetController@addAccountToCurrent')
        ->name('add-account-to-current');

    Route::get('/propose/create-budget-range', 'BudgetController@createRangeView')
        ->name('createBudgetProposal');

    Route::post('/propose/create', 'BudgetController@createEmptyBudget')->name('emptyBudget');

    Route::post('/propose/modify', 'BudgetController@modifyAccount');

    Route::get('/propose/print', 'BudgetController@printView');

    Route::get('/propose/save', 'BudgetController@saveBudget');

    Route::get('/propose/add', 'BudgetController@getAccount')->name('editBudgetProposal');

    Route::post('/add-account-proposal', 'BudgetController@addAccount')->name('add_account');

    Route::get('/propose/add/{primary_account}', 'BudgetController@getAccount');

    Route::get('/propose/add/{primary_account}/{secondary_account}', 'BudgetController@getAccount');

    Route::post('propose/submit_budget', 'BudgetController@submitBudget')->name('submit_budget'); //unused
});

//Petty Cash Routes
Route::get('/request/petty_cash', 'PettyCashController@requestPettyCashForm')->name('request_petty_cash');

Route::get('/petty_cash', 'PettyCashController@pettyCashView')->name('pettyCashView');

Route::post('/request/petty_cash/record', 'PettyCashController@recordRequestPCV')->name('recordRequestPCV');

Route::post('/petty_cash/cancel', 'PettyCashController@cancelPettyCashRequest')->name('cancelPettyCash');

Route::post('/petty_cash/approve', 'PettyCashController@approvePettyCashRequest')->name('approvePettyCash');

Route::post('/petty_cash/receive/form', 'PettyCashController@receivePettyCashForm')->name('receivePettyCashForm');

Route::post('/petty_cash/receive', 'PettyCashController@receivePettyCash')->name('receivePettyCash');

Route::post('/petty_cash/deny', 'PettyCashController@denyPettyCash')->name('denyPettyCash');

Route::post('/petty_cash/refill', 'PettyCashController@pcrf')->name('pcrf');

//Request Access Accounts Routes
Route::get('/accounts', 'AccountController@accessedAccountsView')->name('accessedAccountsView');

Route::get('/accounts/request', 'AccountController@requestAccessForm')->name('requestAccessForm');

Route::post('/accounts/request/save', 'AccountController@requestAccessSave')->name('requestAccessSave');

Route::get('/request-accounts', 'AccountController@requestsForAccess')->name('requestsForAccess');

Route::post('/request-accounts/response', 'AccountController@respondRequest')->name('respondRequest');

//BRF Routes
Route::get('/brf', 'BRFController@brfView')->name('brfView');

Route::get('/brf/add', 'BRFController@brfAdd')->name('brfAdd');

Route::post('brf/add/save', 'BRFController@saveBRF')->name('saveBRF');

Route::post('/brf/access', 'BRFController@accessBRF')->name('brfAccess');

Route::post('/brf/print', 'BRFController@printBRF')->name('printBRF');

Route::post('/brf/access/saveAmount', 'BRFController@saveAmount')->name('saveAmountBRF');

Route::post('/brf/add/testResults', 'BRFController@testResults')->name('testResults');

//MRF Routes
Route::get('/mrf', 'MRFController@viewMRF')->name('viewMRF');

Route::get('/mrf/add', 'MRFController@addMRFView')->name('addMRFView');

Route::post('/mrf/add/entry', 'MRFController@ajaxAddEntry')->name('ajaxAddEntry');

Route::post('/mrf/save', 'MRFController@saveMRF')->name('saveMRF');

Route::get('/mrf/exec', 'MRFController@execMRFView')->name('execMRF');

Route::post('/mrf/approve', 'MRFController@approveMRF')->name('approveMRF');

Route::post('/mrf/print', 'MRFController@printMRF')->name('printMRF');

Route::post('/mrf/receive-amounts', 'MRFController@receiveAmounts')->name('receiveAmountsMRF');

Route::post('/mrf/saveAmounts', 'MRFController@saveAmounts')->name('saveAmountsMRF');

//Other Transactions Routes
Route::get('/transactions', 'TransactionController@transacView')->name('transacView');

Route::get('/transactions/add', 'TransactionController@addTransaction')->name('addTransaction');

Route::post('/transactions/add/save', 'TransactionController@saveTransaction')->name('saveTransaction');

//Journal Routes

Route::get('/pickPrimary', 'JournalController@primaryAccounts')->name('pickPrimary');

Route::get('/journal', 'JournalController@journalPrimary')->name('disbursementJournal');

Route::post('/journal/prsForm', 'PRSController@prsForm')->name('prsForm');

Route::post('/journal/prs', 'PRSController@savePRS')->name('generatePRS');

Route::get('/prs', 'PRSController@getPRS')->name('getPRS');

Route::get('/ledger', 'JournalController@primaryLedger')->name('primaryLedger');

Route::post('/ledger/primary', 'JournalController@getLedger')->name('getLedger');

Route::post('/adjustEntry', 'JournalController@adjustForm')->name('adjustForm');

Route::post('/adjustEntry/save', 'JournalController@adjustEntry')->name('adjustEntry');

//Report Routes

Route::get('/reports/list_of_accessed_users', 'ReportsController@getListOfUsers');

Route::get('/accounts_activity/primary', 'ReportsController@accountsActivityPA')->name('accountsActivityPA');

Route::get('/accounts_activity/secondary', 'ReportsController@accountsActivitySA')->name('accountsActivitySA');

Route::get('/accounts_activity/tertiary', 'ReportsController@accountsActivityTA')->name('accountsActivityTA');

Route::get('/report/transactions', 'ReportsController@transactionsToday')->name('transactionsToday');

Route::get('/report/budget_variance', 'ReportsController@budgetVariance')->name('budgetVariance');