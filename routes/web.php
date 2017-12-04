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
Route::get('/', function(){
    if(Auth::guest())
        return view('homepage');
    else if(Auth::user()->usertype == "System Admin"){
        return redirect("/all-users");
    }else if(Auth::user()->usertype == "Executive"){
        return redirect("/request-accounts");
    }else if(Auth::user()->usertype == "Budget Requestee"){
        return redirect('/accounts');
    }else{
        return view('homepage');
    }
})->name('homepage');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/all-users', 'AdminController@getAllUsers')->name('get-all-users');

Route::get('/add_user', function (){
    if(Auth::guest())
        return redirect('unauthorized_access');
    else if (Auth::user()->usertype == "System Admin")
        return view('addUsers');
    else
        return redirect('unauthorized_access');
})->name('add_user');

Route::get('/edit_account', 'AdminController@editAccount')->name('edit_account');

Route::post('/edit_account/submit', 'AdminController@saveChangesToAccount')->name('save_account');

Route::post('/add_user/submit', 'AdminController@createUser')->name('create_user');

Route::get('/unauthorized_access', function (){
    return view('unauthorizedAccess');
})->name('unauthorized_access');

//Petty Cash Routes
Route::get('/request/petty_cash', 'PettyCashController@requestPettyCashForm')->name('request_petty_cash');

Route::get('/petty_cash', 'PettyCashController@pettyCashView')->name('pettyCashView');

Route::post('/request/petty_cash/record', 'PettyCashController@recordRequestPCV')->name('recordRequestPCV');

Route::post('/petty_cash/cancel', 'PettyCashController@cancelPettyCashRequest')->name('cancelPettyCash');

Route::post('/petty_cash/approve', 'PettyCashController@approvePettyCashRequest')->name('approvePettyCash');

Route::post('/petty_cash/receive/form', 'PettyCashController@receivePettyCashForm')->name('receivePettyCashForm');

Route::post('/petty_cash/receive', 'PettyCashController@receivePettyCash')->name('receivePettyCash');

Route::post('/petty_cash/deny', 'PettyCashController@denyPettyCash')->name('denyPettyCash');

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

//Budget Proposal Routes

Route::get('/links', 'BudgetController@showLinks');

Route::get('/propose/create-budget-range', 'BudgetController@createRangeView')
    ->name('createBudgetProposal');

Route::get('/propose/create', 'BudgetController@createEmptyBudget');

Route::post('/propose/modify', 'BudgetController@modifyAccount');

Route::get('/propose/print', 'BudgetController@printView');

Route::get('/propose/save', 'BudgetController@saveBudget');

Route::get('/propose/add', 'BudgetController@getAccount')->name('editBudgetProposal');

Route::post('/add-account-proposal', 'BudgetController@addAccount')->name('add_account');

Route::get('/propose/add/{primary_account}', 'BudgetController@getAccount');

Route::get('/propose/add/{primary_account}/{secondary_account}', 'BudgetController@getAccount');

Route::post('propose/submit_budget', 'BudgetController@submitBudget')->name('submit_budget'); //unused

//Journal Routes

Route::get('/pickPrimary', 'JournalController@primaryAccounts')->name('pickPrimary');

Route::get('/journal', 'JournalController@journalPrimary')->name('disbursementJournal');



