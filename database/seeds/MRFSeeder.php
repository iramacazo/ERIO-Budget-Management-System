<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MRFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('material_requisition_forms')->insert([
           'form_num' => '123456',
           'date_needed' => Carbon::now()->addYear(),
           'requested_by' => 1,
           'contact_person' => 'Paolo',
           'contact_person_email' => 'paolo@gmail.com',
           'place_of_delivery' => 'hssh',
           'dept' => 'vperi',
           'list_pa_id' => 1
        ]);

        $acc = DB::table('accessed_tertiary_accounts')
                ->join('list_of_tertiary_accounts', 'list_of_tertiary_accounts.id',
                    '=', 'accessed_tertiary_accounts.list_id')
                ->where('list_of_tertiary_accounts.list_id', 1)
                ->where('accessed_tertiary_accounts.user_id', 1)->get();
        $mrf = DB::table('material_requisition_forms')->first();

        foreach($acc as $a){
            DB::table('material_requisition_form_entries')->insert([
                'description' => 'test',
                'quantity' => 2,
                'list_ta_id' => $a->id,
                'mrf_id' => $mrf->id
            ]);
        }
    }
}
