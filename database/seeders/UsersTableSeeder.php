<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating a user with the specified username and password
        DB::table('users')->insert([
            'name' => 'Adminn',
            'username' => 'tes@gmail.com',
            'password' => Hash::make('test'),
        ]);
    }
}
