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
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        AccessedSecondaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 1,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 1,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 2,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 3,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedPrimaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 2,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        AccessedSecondaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 4,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 5,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 10,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 11,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        AccessedTertiaryAccounts::insert([
            'user_id' => $user->id,
            'explanation' => 'Testing',
            'list_id' => 12,
            'status' => 'Open',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
