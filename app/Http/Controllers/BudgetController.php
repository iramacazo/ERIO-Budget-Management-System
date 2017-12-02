<?php

namespace App\Http\Controllers;

use App\ListOfSecondaryAccounts;
use App\ListOfTertiaryAccounts;
use App\SecondaryAccounts;
use App\TertiaryAccounts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Budget;
use App\PrimaryAccounts;
use App\ListOfPrimaryAccounts;
use DB;

class BudgetController extends Controller
{

    public function viewProposeBudget(){
        $budgetId = Budget::where([
            ['approved_by_vp', '=', '1'],
            ['approved_by_acc', '=', '1'],
        ])->orderBy('created_at', 'desc')
            ->first()
            ->pluck('id');

        $budgetP = Budget::where('approved_by_vp', '=', '0')
            ->orWhere('approved_by_acc', '=', '0')
            ->get();

        if($budgetP->isEmpty()){
            $primary_accounts = DB::table('primary_accounts')
                                    ->select('name')
                                    ->join('list_of_primary_accounts', 'list_of_primary_accounts.account_id', '=',
                                        'primary_accounts.id')
                                    ->join('budgets', 'budgets.id', '=', 'list_of_primary_accounts.budget_id')
                                    ->where('budget_id', '=', $budgetId)
                                    ->groupBy('name')
                                    ->get();

            $secondary_accounts = DB::table('secondary_accounts')
                                    ->select('name')
                                    ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.id', '=',
                                        'secondary_accounts.account_id')
                                    ->join('list_of_primary_accounts', 'list_of_primary_accounts.id', '=',
                                        'list_of_secondary_accounts.account_id')
                                    ->join('budgets', 'budgets.id', '=', 'list_of_primary_accounts.budget_id')
                                    ->where('budget_id', '=', $budgetId)
                                    ->groupBy('name')
                                    ->get();

            $tertiary_accounts = DB::table('tertiary_accounts')
                                    ->select('name')
                                    ->join('list_of_tertiary_accounts', 'tertiary_accounts.subaccount_id', '=',
                                        'list_of_tertiary_accounts.id')
                                    ->join('list_of_secondary_accounts', 'list_of_tertiary_accounts.account_id', '=',
                                        'list_of_secondary_accounts.id')
                                    ->join('list_of_primary_accounts', 'list_of_secondary_accounts.account_id', '=',
                                        'list_of_primary_accounts.id')
                                    ->join('budgets', 'budgets.id', '=', 'list_of_primary_accounts.budget_id')
                                    ->where('budget_id', '=', $budgetId)
                                    ->groupBy('name')
                                    ->get();

            return view('proposal/proposeBudget', [
                'primary_accounts' => $primary_accounts,
                'secondary_accounts' => $secondary_accounts,
                'tertiary_accounts' => $tertiary_accounts
            ]); //return to view with data of previous years budget
        }
    }

    /*
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
        //TDO add boolean sa budgets DB to know if executive approves of budget
        $budget->approved_by_acc = false;
        $budget->approved_by_vp = false;
        $budget->save();


        $pal_max_id = ListOfPrimaryAccounts::max('id');
        if(is_null($pal_max_id))   //check max primary account list id
            $pal_max_id=1;
        else
            $pal_max_id++;
        $primary_accounts_list->id = $pal_max_id;
         //autoincrement na ba to

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
*/ //old submit proposal

    public function addAccount(Request $request){
        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'budget' => 'required|numeric|min:1'
        ]);

        if(isset($request->account_p) && !isset($request->account_s)){
            if($validator->fails()){
                return redirect('/propose/'.$request->account_p)
                    ->withErrors($validator)
                    ->withInput();
            }

            $this->addSecondaryAccount($request->account_p, $request->account, $request->budget);

            return redirect('/propose/'.$request->account_p);
        }

        if(isset($request->account_s) && isset($request->account_p)){
            if($validator->fails()){
                return redirect('/propose/'.$request->account_p.'/'.$request->account_s)
                    ->withErrors($validator)
                    ->withInput();
            }

            $this->addTertiaryAccount($request->account_p, $request->account_s, $request->account, $request->budget);

            return redirect('/propose/'.$request->account_p.'/'.$request->account_s);
        }

        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'budget' => 'required|numeric|min:1',
            'code' => 'required|numeric'
        ]);

        if($validator->fails()){
            return redirect('/propose/'.$request->account_p.'/'.$request->account_s)
                ->withErrors($validator)
                ->withInput();
        }

        $this->addPrimaryAccount($request->account, $request->budget, $request->code);

        return redirect('/propose');
    }

    //get id of budget proposal
    public function getProposalBudgetId(){
        $proposal_id = DB::table('budgets')
                        ->select('id')
                        ->where('approved_by_vp', '=', '0')
                        ->orWhere('approved_by_acc', '=', '0')
                        ->get();

        foreach($proposal_id as $p){
            $proposal_id = $p->id;
        }

        return $proposal_id;
    }

    //add new secondary account
    public function addSecondaryAccount($primary_account_ref, $name, $budget){
        $account = new SecondaryAccounts();
        $account->name = $name;
        $account->account_id = $this->getPrimaryAccountId($primary_account_ref);
        $account->save();

        $list_account = new ListOfSecondaryAccounts();
        $list_account->account_id = $account->id;
        $list_account->amount = $budget;
        $list_account->list_id = $this->getPrimaryListId($primary_account_ref);
        $list_account->save();

        $pid = $this->getPrimaryListId($primary_account_ref);
        $primary_list = ListOfPrimaryAccounts::find($pid);
        $primary_list->amount = $this->getPrimaryAccountBudget($primary_account_ref);
        $primary_list->save();
    }

    //get all secondary accounts
    public function getSecondaryAccounts($primary_account){
        $sub_accounts = DB::table('budgets')
            ->select('secondary_accounts.name', 'list_of_secondary_accounts.amount',
                'list_of_tertiary_accounts.list_id')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                '=', 'budgets.id')
            ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id', '=',
                'list_of_primary_accounts.id')
            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                'list_of_secondary_accounts.account_id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->leftJoin('list_of_tertiary_accounts', 'list_of_tertiary_accounts.list_id', '=',
                'list_of_secondary_accounts.id')
            ->where([
                ['primary_accounts.name', '=', $primary_account],
                ['approved_by_vp', '=', '0']])
            ->orWhere([
                ['primary_accounts.name', '=', $primary_account],
                ['approved_by_acc', '=', '0']])
            ->groupBy('secondary_accounts.name')
            ->get();

        return $sub_accounts;
    }

    //get id of list of secondary accounts
    public function getSecondaryListId($primary_account_ref, $secondary_account_ref){
        $secondary_list_id = DB::table('budgets')
                                ->select('list_of_secondary_accounts.id')
                                ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                                    '=','budgets.id')
                                ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id',
                                    '=', 'list_of_primary_accounts.id')
                                ->join('primary_accounts', 'primary_accounts.id', '=',
                                    'list_of_primary_accounts.account_id')
                                ->join('secondary_accounts', 'secondary_accounts.id', '=',
                                    'list_of_secondary_accounts.account_id')
                                ->where([
                                    ['secondary_accounts.name', '=', $secondary_account_ref],
                                    ['primary_accounts.name', '=', $primary_account_ref],
                                    ['approved_by_vp', '=', '0']])
                                ->orWhere([
                                    ['secondary_accounts.name', '=', $secondary_account_ref],
                                    ['primary_accounts.name', '=', $primary_account_ref],
                                    ['approved_by_acc', '=', '0']])
                                ->get();

        foreach($secondary_list_id as $sec){
            $list_id = $sec->id;
        }

        return $list_id;
    }

    //get id of secondary account
    public function getSecondaryAccountId($primary_account_ref, $secondary_account_ref){
        $secondary_account_id = DB::table('budgets')
                        ->select('secondary_accounts.id')
                        ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                            '=','budgets.id')
                        ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id',
                            '=', 'list_of_primary_accounts.id')
                        ->join('primary_accounts', 'primary_accounts.id', '=',
                            'list_of_primary_accounts.account_id')
                        ->join('secondary_accounts', 'secondary_accounts.id', '=',
                            'list_of_secondary_accounts.account_id')
                        ->where([
                            ['secondary_accounts.name', '=', $secondary_account_ref],
                            ['primary_accounts.name', '=', $primary_account_ref],
                            ['approved_by_vp', '=', '0']])
                        ->orWhere([
                            ['secondary_accounts.name', '=', $secondary_account_ref],
                            ['primary_accounts.name', '=', $primary_account_ref],
                            ['approved_by_acc', '=', '0']])
                        ->get();

        foreach($secondary_account_id as $sec){
            $sec_acc_id = $sec->id;
        }

        return $sec_acc_id;
    }

    //get ids of both secondary list and secondary account itself
    public function getSecondaryAccountAndListId($primary_account_ref, $secondary_account_ref){
        $secondary_account_ids = DB::table('budgets')
            ->select('list_of_secondary_accounts.id as lsid', 'secondary_accounts.id as sid')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id', '=',
                'budgets.id')
            ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id', '=',
                'list_of_primary_accounts.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                'list_of_secondary_accounts.account_id')
            ->where([
                ['approved_by_vp', '=', '0'],
                ['primary_accounts.name', '=', $primary_account_ref],
                ['secondary_accounts.name', '=', $secondary_account_ref]])
            ->orWhere([
                ['approved_by_acc', '=', '0'],
                ['primary_accounts.name', '=', $primary_account_ref],
                ['secondary_accounts.name', '=', $secondary_account_ref]])
            ->groupBy('primary_accounts.name')
            ->get();

        return $secondary_account_ids;
    }

    //get total budget of a secondary account
    public function getSecondaryAccountBudget($primary_account_ref, $secondary_account_ref){
        $sub_accounts = $this->getTertiaryAccounts($secondary_account_ref, $primary_account_ref);

        $total_budget = 0;

        foreach($sub_accounts as $sa){
            $total_budget+=$sa->amount;
        }

        return $total_budget;
    }

    //add new primary account
    public function addPrimaryAccount($name, $budget, $code){
        $account = new PrimaryAccounts();
        $account->name = $name;
        $account->code = $code;
        $account->save();

        $list_account = new ListOfPrimaryAccounts();
        $list_account->account_id = $account->id;
        $list_account->budget_id = $this->getProposalBudgetId(); //
        $list_account->amount = $budget;
        $list_account->save();
    }

    //get id of list of primary accounts
    public function getPrimaryListId($primary_account_ref){
        $primary_list_id = DB::table('budgets')
                                ->select('list_of_primary_accounts.id')
                                ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                                    '=', 'budgets.id')
                                ->join('primary_accounts', 'primary_accounts.id', '=',
                                    'list_of_primary_accounts.account_id')
                                ->where([
                                    ['approved_by_vp', '=', '0'],
                                    ['primary_accounts.name', '=', $primary_account_ref]])
                                ->orWhere([
                                    ['approved_by_acc', '=', '0'],
                                    ['primary_accounts.name', '=', $primary_account_ref]])
                                ->get();

        foreach($primary_list_id as $pri){
            $list_id = $pri->id;
        }

        return $list_id;
    }

    //get id of primary account
    public function getPrimaryAccountId($primary_account_ref){
        $primary_account_id = DB::table('budgets')
                            ->select('primary_accounts.id')
                            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                                '=','budgets.id')
                            ->join('primary_accounts', 'primary_accounts.id', '=',
                                'list_of_primary_accounts.account_id')
                            ->where([
                                ['primary_accounts.name', '=', $primary_account_ref],
                                ['approved_by_vp', '=', '0']])
                            ->orWhere([
                                ['primary_accounts.name', '=', $primary_account_ref],
                                ['approved_by_acc', '=', '0']])
                            ->get();

        foreach($primary_account_id as $prim){
            $acc_id = $prim->id;
        }

        return $acc_id;
    }

    //get total budget of a primary account
    public function getPrimaryAccountBudget($primary_account_ref){
        $sub_accounts = $this->getSecondaryAccounts($primary_account_ref);

        $total_budget = 0;

        foreach($sub_accounts as $sa){
            $total_budget+=$sa->amount;
        }

        return $total_budget;
    }

    //create an empty budget
    public function createEmptyBudget(Request $request){
        //isa lang pwedeng empty budget

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $budget = new Budget();
        $budget->start_range = $start_date;
        $budget->end_range = $end_date;
        $budget->approved_by_acc = 0;
        $budget->approved_by_vp = 0;
        $budget->save();

        return redirect('propose/'); //redirect to add accounts page
    }

    //get all primary accounts
    public function getPrimaryAccounts(){
        $list = DB::table('budgets')
            ->select('list_of_primary_accounts.amount', 'list_of_secondary_accounts.list_id',
                'primary_accounts.name', 'primary_accounts.code', 'budgets.start_range', 'budgets.end_range')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id', '=',
                'budgets.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->leftJoin('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id', '=',
                'list_of_primary_accounts.id')
            ->where('approved_by_vp', '=', '0')
            ->orWhere('approved_by_acc', '=', '0')
            ->groupBy('primary_accounts.name')
            ->orderBy('list_of_primary_accounts.created_at', 'asc')
            ->get();

        return $list;
    }

    //get individual primary account
    public function getPrimaryAccountAndListId($primary_account_ref){
        $primary_account_ids = DB::table('budgets')
            ->select('list_of_primary_accounts.id as lpid', 'primary_accounts.id as pid')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id', '=',
                'budgets.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->where([
                ['approved_by_vp', '=', '0'],
                ['primary_accounts.name', '=', $primary_account_ref]])
            ->orWhere([
                ['approved_by_acc', '=', '0'],
                ['primary_accounts.name', '=', $primary_account_ref]])
            ->groupBy('primary_accounts.name')
            ->get();

        return $primary_account_ids;
    }

    /*
    public function getPrimaryAccountName($primary_account){
        $account_name = DB::table('budgets')
                            ->select('name')
                            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                                '=', 'budgets.id')
                            ->join('primary_accounts', 'primary_accounts.id', '=',
                                'list_of_primary_accounts.account_id')
                            ->where([
                                ['primary_accounts.name', '=', $primary_account],
                                ['approved_by_vp', '=', '0']])
                            ->orWhere([
                                ['primary_accounts.name', '=', $primary_account]])
                            ->get();

        return $account_name;
    }

    public function getSecondaryAccountName($secondary_account, $primary_account){
        $account_name = DB::table('budgets')
            ->select('secondary_account.name')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                '=', 'budgets.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->where([
                ['primary_accounts.name', '=', $primary_account],
                ['secondary_accounts.name', '=', $secondary_account],
                ['approved_by_vp', '=', '0']])
            ->orWhere([
                ['primary_accounts.name', '=', $primary_account],
                ['secondary_accounts.name', '=', $secondary_account]])
            ->get();

        return $account_name;
    }
    */ //get account names

    //add new tertiary account
    public function addTertiaryAccount($primary_account_ref, $secondary_account_ref, $name, $budget){
        $account = new TertiaryAccounts();
        $account->name = $name;
        $account->subaccount_id = $this->getSecondaryAccountId($primary_account_ref, $secondary_account_ref);
        $account->save();

        $list_account = new ListOfTertiaryAccounts();
        $list_account->account_id = $account->id;
        $list_account->list_id  = $this->getSecondaryListId($primary_account_ref, $secondary_account_ref);
        $list_account->amount = $budget;
        $list_account->save();

        $sid = $this->getSecondaryListId($primary_account_ref, $secondary_account_ref);
        $secondary_list = ListOfSecondaryAccounts::find($sid);
        $secondary_list->amount = $this->getSecondaryAccountBudget($primary_account_ref, $secondary_account_ref);
        $secondary_list->save();

        $pid = $this->getPrimaryListId($primary_account_ref);
        $primary_list = ListOfPrimaryAccounts::find($pid);
        $primary_list->amount = $this->getPrimaryAccountBudget($primary_account_ref);
        $primary_list->save();

    }

    public function getTertiaryListId($primary_account_ref, $secondary_account_ref, $tertiary_account_ref){
        $tertiary_list_id = DB::table('budgets')
            ->select('list_of_tertiary_accounts.id')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                '=','budgets.id')
            ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id',
                '=', 'list_of_primary_accounts.id')
            ->join('list_of_tertiary_accounts', 'list_of_tertiary_accounts.list_id', '=',
                'list_of_secondary_accounts.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                'list_of_secondary_accounts.account_id')
            ->join('tertiary_accounts', 'tertiary_accounts.id', '=',
                'list_of_tertiary_accounts.account_id')
            ->where([
                ['tertiary_accounts.name', '=', $tertiary_account_ref],
                ['secondary_accounts.name', '=', $secondary_account_ref],
                ['primary_accounts.name', '=', $primary_account_ref],
                ['approved_by_vp', '=', '0']])
            ->orWhere([
                ['tertiary_accounts.name', '=', $tertiary_account_ref],
                ['secondary_accounts.name', '=', $secondary_account_ref],
                ['primary_accounts.name', '=', $primary_account_ref],
                ['approved_by_acc', '=', '0']])
            ->get();

        foreach($tertiary_list_id as $ter){
            $list_id = $ter->id;
        }

        return $list_id;
    }

    public function getTertiaryAccountId($primary_account_ref, $secondary_account_ref, $tertiary_account_ref){
        $tertiary_account_id = DB::table('budgets')
            ->select('tertiary_accounts.id')
            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                '=','budgets.id')
            ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id',
                '=', 'list_of_primary_accounts.id')
            ->join('list_of_tertiary_accounts', 'list_of_tertiary_accounts.list_id', '=',
                'list_of_secondary_accounts.id')
            ->join('primary_accounts', 'primary_accounts.id', '=',
                'list_of_primary_accounts.account_id')
            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                'list_of_secondary_accounts.account_id')
            ->join('tertiary_accounts', 'tertiary_accounts.id', '=',
                'list_of_tertiary_accounts.account_id')
            ->where([
                ['tertiary_accounts.name', '=', $tertiary_account_ref],
                ['secondary_accounts.name', '=', $secondary_account_ref],
                ['primary_accounts.name', '=', $primary_account_ref],
                ['approved_by_vp', '=', '0']])
            ->orWhere([
                ['tertiary_accounts.name', '=', $tertiary_account_ref],
                ['secondary_accounts.name', '=', $secondary_account_ref],
                ['primary_accounts.name', '=', $primary_account_ref],
                ['approved_by_acc', '=', '0']])
            ->get();

        foreach($tertiary_account_id as $ter){
            $ter_acc_id = $ter->id;
        }

        return $ter_acc_id;
    }

    //get all tertiary accounts
    public function getTertiaryAccounts($secondary_account, $primary_account){
        $sub_accounts = DB::table('budgets')
                            ->select('tertiary_accounts.name', 'list_of_tertiary_accounts.amount')
                            ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                                '=', 'budgets.id')
                            ->join('list_of_secondary_accounts', 'list_of_secondary_accounts.list_id',
                                '=', 'list_of_primary_accounts.id')
                            ->join('list_of_tertiary_accounts', 'list_of_tertiary_accounts.list_id',
                                '=','list_of_secondary_accounts.id')
                            ->join('tertiary_accounts', 'tertiary_accounts.id', '=',
                                'list_of_tertiary_accounts.account_id')
                            ->join('secondary_accounts', 'secondary_accounts.id', '=',
                                'list_of_secondary_accounts.account_id')
                            ->join('primary_accounts', 'primary_accounts.id', '=',
                                'list_of_primary_accounts.account_id')
                            ->where([
                                ['secondary_accounts.name', '=', $secondary_account],
                                ['primary_accounts.name', '=', $primary_account],
                                ['approved_by_vp', '=', '0']])
                            ->orWhere([
                                ['secondary_accounts.name', '=', $secondary_account],
                                ['primary_accounts.name', '=', $primary_account],
                                ['approved_by_acc', '=', '0']])
                            ->get();

        return $sub_accounts;
    }

    //get all accounts
    public function getAccount($primary_account = null, $secondary_account = null){
        if($this->getProposalBudgetId() == null)
            return redirect('propose/create-budget-range');
        if($primary_account && $secondary_account){
            $sub_accounts = $this->getTertiaryAccounts($secondary_account, $primary_account);
            return view('proposal/AddAccount', [
                'account_1' => $primary_account,
                'account_2' => $secondary_account,
                'accounts' => $sub_accounts,

            ]);
        }
        else if($primary_account){
            $sub_accounts = $this->getSecondaryAccounts($primary_account);
            return view('proposal/AddAccount', [
                'account_1' => $primary_account,
                'accounts' => $sub_accounts
            ]);
        }
        else{
            $primary_accounts_list = $this->getPrimaryAccounts();
            $total_budget = DB::table('list_of_primary_accounts')
                            ->select('amount')
                            ->where('budget_id', '=', $this->getProposalBudgetId())
                            ->sum('amount');

            return view('proposal/AddAccount', [
                'accounts' => $primary_accounts_list,
                'pa' => true,
                'total_budget' => $total_budget
            ]);
        }

    }

    //
    public function getPreviousYearBudgetId(){
        $budget_id = DB::table('budgets')
                    ->where([
                        ['approved_by_vp', '=', '1'],
                        ['approved_by_acc', '=', '1']
                    ])->orderBy('start_range', 'desc')
                    ->value('id');

        return $budget_id;
    }

    //get previous year primary accounts
    public function getPreviousYearAccounts(){
        $list = DB::table('budgets')
                ->select('list_of_primary_accounts.amount', 'primary_accounts.name', 'primary_accounts.code',
                    'list_of_primary_accounts.id', 'budgets.start_range', 'budgets.end_range')
                ->join('list_of_primary_accounts', 'list_of_primary_accounts.budget_id',
                    '=', $this->getPreviousYearBudgetId())
                ->join('primary_accounts', 'primary_accounts.id', '=',
                    'list_of_primary_accounts.account_id')
                ->where('budgets.id', '=', $this->getProposalBudgetId())
                ->groupBy('primary_accounts.name')
                ->get();

        return $list;
    }

    //// -- modify account functions

    public function modifyAccount(Request $request){
        if($request->submit == 'Edit'){
            $validator = Validator::make($request->all(), [
                'code' => 'required_without_all:account,budget',
                'account' => 'required_without_all:code,budget',
                'budget' => 'required_without_all:code,account'
            ]);

            if($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator);
            }

            $this->editAccount($request->primary_account, $request->secondary_account, $request->tertiary_account,
                $request->account, $request->budget, $request->code);
            return redirect()
                ->back();
        }
        else if($request->submit == 'Delete'){
            $this->deleteAccount($request->primary_account, $request->secondary_account, $request->tertiary_account);

            return redirect()
                ->back();
        }
    }

    //edit an account
    public function editAccount($primary_account, $secondary_account, $tertiary_account, $name, $budget, $code){
        if($tertiary_account != null){
            $tid = $this->getTertiaryAccountId($primary_account, $secondary_account, $tertiary_account);
            $account = TertiaryAccounts::find($tid);
            $lid = $this->getTertiaryListId($primary_account, $secondary_account, $tertiary_account);
            $list = ListOfTertiaryAccounts::find($lid);

            if($name != null){
                $account->name = $name;
            }

            if($budget != null){
                $list->amount = $budget;
                $list->save();

                $sid = $this->getSecondaryListId($primary_account, $secondary_account);
                $secondary_list = ListOfSecondaryAccounts::find($sid);
                $secondary_list->amount = $this->getSecondaryAccountBudget($primary_account, $secondary_account);
                $secondary_list->save();

                $pid = $this->getPrimaryListId($primary_account);
                $primary_list = ListOfPrimaryAccounts::find($pid);
                $primary_list->amount = $this->getPrimaryAccountBudget($primary_account);
                $primary_list->save();
            }

            $account->save();

        }
        else if($secondary_account != null){
            $sid = $this->getSecondaryAccountId($primary_account, $secondary_account);
            $account = SecondaryAccounts::find($sid);
            $lid = $this->getSecondaryListId($primary_account, $secondary_account);
            $list = ListOfSecondaryAccounts::find($lid);

            if($name != null){
                $account->name = $name;
            }

            if($budget != null){
                $list->amount = $budget;
                $list->save();

                $pid = $this->getPrimaryListId($primary_account);
                $primary_list = ListOfPrimaryAccounts::find($pid);
                $primary_list->amount = $this->getPrimaryAccountBudget($primary_account);
                $primary_list->save();

            }

            $account->save();
        }
        else if($primary_account != null){
            $pid = $this->getPrimaryAccountId($primary_account);
            $account = PrimaryAccounts::find($pid);
            $lid = $this->getPrimaryListId($primary_account);
            $list = ListOfPrimaryAccounts::find($lid);

            if($name != null){
                $account->name = $name;
            }

            if($budget != null){
                $list->amount = $budget;
                $list->save();
            }

            if($code != null){
                $account->code = $code;
            }

            $account->save();
        }
    }

    //delete an account
    public function deleteAccount($primary_account, $secondary_account, $tertiary_account){
        if($tertiary_account != null){
            $tid = $this->getTertiaryAccountId($primary_account, $secondary_account, $tertiary_account);
            TertiaryAccounts::destroy($tid);

            $sid = $this->getSecondaryListId($primary_account, $secondary_account);
            $secondary_list = ListOfSecondaryAccounts::find($sid);
            $secondary_list->amount = $this->getSecondaryAccountBudget($primary_account, $secondary_account);
            $secondary_list->save();

            $pid = $this->getPrimaryListId($primary_account);
            $primary_list = ListOfPrimaryAccounts::find($pid);
            $primary_list->amount = $this->getPrimaryAccountBudget($primary_account);
            $primary_list->save();

        }
        else if($secondary_account != null){
            $ids = $this->getSecondaryAccountAndListId($primary_account, $secondary_account);
            foreach($ids as $i){
                SecondaryAccounts::destroy($i->sid);
                ListOfSecondaryAccounts::destroy($i->lsid);
            }

            $pid = $this->getPrimaryListId($primary_account);
            $primary_list = ListOfPrimaryAccounts::find($pid);
            $primary_list->amount = $this->getPrimaryAccountBudget($primary_account);
            $primary_list->save();

        }
        else if($primary_account != null){
            $ids = $this->getPrimaryAccountAndListId($primary_account);
            foreach($ids as $i){
                PrimaryAccounts::destroy($i->pid);
                ListOfPrimaryAccounts::destroy($i->lpid);
            }
        }
    }

    //  -- redirect to view functions

    public function showLinks(){ //TEMPO
        return view('proposal/links');
    }

    public function createRangeView(){
        if($this->getProposalBudgetId()->isEmpty()){
            return view('proposal/addRange');
        }
        else return redirect('/propose');
    }

    public function printView(){
        $primary_accounts = $this->getPrimaryAccounts();
        $previous_primaries = $this->getPreviousYearAccounts();

        $proposed_date = DB::table('budgets')
                        ->where('id', '=', $this->getProposalBudgetId())
                        ->get();

        foreach($proposed_date as $p){
            $start_date = new Carbon($p->start_range);
            $start_year = $start_date->year;
            $end_date = new Carbon($p->end_range);
            $end_year = $end_date->format('y');
            $proposed_ay = $start_year.'-'.$end_year;
        }

        foreach($previous_primaries as $prv){
            $start_date = new Carbon($prv->start_range);
            $start_year = $start_date->year;
            $end_date = new Carbon($p->end_range);
            $end_year = $end_date->format('y');
            $previous_ay = $start_year.'-'.$end_year;
        }

        return view('proposal/printProposal', [
            'primary_accounts_list' => $primary_accounts,
            'prev_primary_accounts_list' => $previous_primaries,
            'proposed_ay' => $proposed_ay,
            'previous_ay' => $previous_ay
        ]);


    }

}

//TODO approval of budget proposal na lang kulang
//no ketchup