<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\PrimaryAccounts;
use App\SecondaryAccounts;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('budgets')->insert([
            'start_range' => Carbon::now(),
            'end_range' => Carbon::now()->addYear(),
            'approved_by_vp' => true,
            'approved_by_acc' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $budget = DB::table('budgets')->first();
        $pa = PrimaryAccounts::where('name', 'Activities')->first();

        DB::table('list_of_primary_accounts')->insert([
            'budget_id' => $budget->id,
            'account_id' => $pa->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'amount' => 4500000
        ]);

        $la = DB::table('list_of_primary_accounts')->latest()->first();
        $sec = DB::table('secondary_accounts')->where('account_id', $pa->id)->get();

        foreach($sec as $s){
            DB::table('list_of_secondary_accounts')->insert([
                'list_id' => $la->id,
                'account_id' => $s->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'amount' => 1500000
            ]);

            $third = DB::table('tertiary_accounts')->where('subaccount_id', $s->id)->get();

            foreach($third as $t){
                DB::table('list_of_tertiary_accounts')->insert([
                    'list_id' => $s->id,
                    'account_id' => $t->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'amount' => 500000
                ]);
            }
        }
    }
}
