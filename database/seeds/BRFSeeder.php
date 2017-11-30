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
        DB::table('bookstore_requisition_forms')->insert([
            'user_id' => $user->id,
            'list_pa_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('bookstore_requisition_form_entries')->insert([
            'description' => 'test1',
            'quantity' => 1,
            'brf_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('bookstore_requisition_form_entries')->insert([
            'description' => 'test2',
            'quantity' => 2,
            'brf_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('bookstore_requisition_form_entries')->insert([
            'description' => 'test3',
            'quantity' => 3,
            'brf_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
