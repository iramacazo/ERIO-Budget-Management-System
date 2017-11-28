<?php

use Illuminate\Database\Seeder;
use App\User;
use App\AccessedPrimaryAccounts;
use App\AccessedSecondaryAccounts;
use App\AccessedTertiaryAccounts;
use Carbon\Carbon;

class AccessedAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'br@gmail.com')->first();

        AccessedPrimaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        AccessedSecondaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'accessedPA_id' => 1
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'accessedSA_id' => 1
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'accessedSA_id' => 1
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'accessedSA_id' => 1
        ]);

    }
}
