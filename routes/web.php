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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Petty Cash Routes
Route::get('/request/petty_cash', 'PettyCashController@requestPettyCashForm')->name('request_petty_cash');

Route::post('/getSubAccounts', 'PettyCashController@getSubAccounts')->name('getSubAccounts');


Route::get('/propose', function () {
    return view('proposeBudget');
});

Route::post('propose/submit_budget', 'BudgetController@submitBudget')->name('submit_budget');

