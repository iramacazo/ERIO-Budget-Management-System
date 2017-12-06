<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BRFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->where('email', 'br@gmail.com')->first();

    }
}
