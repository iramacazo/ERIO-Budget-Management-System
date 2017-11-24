<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('primary_accounts')->insert([
            'name' => 'Activities',
            'code' => '816010',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $pa = DB::table('primary_accounts')->where('name', 'Activities')->first();

        $subs = array("SYSDEVE");
        array_push($subs, "MOBIDEV");
        array_push($subs, "INFOVIS");
        $third = array("Supplies");
        array_push($third, "Transportation");
        array_push($third, "Meeting Expenses");

        for($i = 0; $i < 3; $i++){
            DB::table('secondary_accounts')->insert([
                'name' => $subs[$i],
                'account_id' => $pa->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $sa = DB::table('secondary_accounts')->where([
                ['account_id', $pa->id],
                ['name', $subs[$i]]
            ])->first();

            foreach($third as $t){
                DB::table('tertiary_accounts')->insert([
                    'name' => $t,
                    'subaccount_id' => $sa->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
