<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Budget;
use App\PrimaryAccounts;
use App\ListOfPrimaryAccounts;

class BudgetController extends Controller
{
    //
    public function submitBudget(Request $request){
        $validator = Validator::make($request->all(),[
            'counter' => 'required|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if($validator->fails()){
            return redirect('/propose')
                ->withErrors($validator);
        }

        $counter = $request->counter; //counter for proposed accounts
        $budget_max_id = Budget::max('id');
        if(is_null($budget_max_id))   //check max budget id
            $budget_max_id=1;
        else
            $budget_max_id++;

        $budget = new Budget();
        $budget->start_range = $request->start_date;
        $budget->end_range = $request->end_date;
        $budget->id = $budget_max_id;
        //TODO add boolean sa budgets DB to know if executive approves of budget
        $budget->save();

        /*
        $pal_max_id = ListOfPrimaryAccounts::max('id');
        if(is_null($pal_max_id))   //check max primary account list id
            $pal_max_id=1;
        else
            $pal_max_id++;
        $primary_accounts_list->id = $pal_max_id;
        */ //autoincrement na ba to

        while($counter>0){
            $account = new PrimaryAccounts();

            $cat = 'account_num_'.$counter;
            $name = $request->$cat;
            //TODO check if empty, if empty break
            $cat2 = 'budget_num_'.$counter;
            $amt = $request->$cat2;
            if(is_null($name)||is_null($amt)){
                $counter--;
                break;
            }
            echo($name);
            $primary_accounts_list = new ListOfPrimaryAccounts();

            $record = PrimaryAccounts::where('name', '=', $name)->first();
            if(is_null($record)){ //check if account exists
                $max_id = PrimaryAccounts::max('id');
                if(is_null($max_id))   //check max primary account id
                    $max_id=1;
                else
                    $max_id++;

                $account->id = $max_id;
                $account->name = $name;
                $account->save();

                $primary_accounts_list->account_id = $max_id;
            }
            else{
                //get id of existing record
                $existing_id = PrimaryAccounts::where('name', '=', $name)->pluck('id');
                $primary_accounts_list->account_id = $existing_id;
            }
            $primary_accounts_list->budget_id = $budget_max_id;
            $primary_accounts_list->amount = $amt;
            $primary_accounts_list->save(); //save to DB

            $counter--;

        }

        return;
    }
}
