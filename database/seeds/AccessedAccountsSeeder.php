<?php

use Illuminate\Database\Seeder;
use App\User;
use App\AccessedPrimaryAccounts;
use App\AccessedSecondaryAccounts;
use App\AccessedTertiaryAccounts;
use Carbon\Carbon;
use App\Budget;

class AccessedAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $br = User::where('name', 'Budget Requestee')->first();
        $exec = User::where('name', 'Executive')->first();
        $budget = Budget::latest()->first();
        $pa = \App\ListOfPrimaryAccounts::where('budget_id', $budget->id)->get()->random();
        $sa = $pa->list_of_secondary_accounts->random();

        $acc = new AccessedSecondaryAccounts();
        $acc->list_id = $sa->id;
        $acc->explanation = 'Seeding to';
        $acc->status = 'Open';
        $acc->approved_by = $exec->id;
        $acc->user_id = $br->id;
        $acc->save();
    }
}
