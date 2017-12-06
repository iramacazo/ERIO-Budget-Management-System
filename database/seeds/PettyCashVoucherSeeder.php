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

    }
}
