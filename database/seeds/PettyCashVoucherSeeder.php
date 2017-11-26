<?php

use Illuminate\Database\Seeder;
use App\User;

class PettyCashVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('usertype', 'Budget Requestee')->first();
        DB::table('petty_cash_vouchers')->insert([
            'purpose' => 'primary test',
            'amount' => 30,
            'list_pa_id' => 1,
            'requested_by' => $user->id,
            'status' => 'Approval',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('petty_cash_vouchers')->insert([
            'purpose' => 'secondary test',
            'amount' => 30,
            'list_sa_id' => 2,
            'status' => 'Approval',
            'requested_by' => $user->id,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('petty_cash_vouchers')->insert([
            'purpose' => 'tertiary test',
            'amount' => 200,
            'list_ta_id' => 9,
            'status' => 'Approval',
            'requested_by' => $user->id,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

    }
}
