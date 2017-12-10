<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Budget Requestee',
            'email' => 'br@gmail.com',
            'password' => bcrypt('123456'),
            'usertype' => 'Budget Requestee',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Budget Admin',
            'email' => 'ba@gmail.com',
            'password' => bcrypt('123456'),
            'usertype' => 'Budget Admin',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Executive',
            'email' => 'exec@gmail.com',
            'password' => bcrypt('123456'),
            'usertype' => 'Executive',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'System Admin',
            'email' => 'sa@gmail.com',
            'password' => bcrypt('123456'),
            'usertype' => 'System Admin',
            'status' => 'active'
        ]);
    }
}
