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
    return view('homepage');
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


Route::get('/propose', function () {
    return view('proposeBudget');
});

Route::post('propose/submit_budget', 'BudgetController@submitBudget')->name('submit_budget');



