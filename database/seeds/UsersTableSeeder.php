<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // insert data to 'users' table of database
        // generate dummy user
        DB::table('users')->insert([

            'email'         => "jane.doe@gmail.com",
            'password'      => bcrypt("secret"),
            'first_name'    => "Jane",
            'last_name'     => "Doe",
            'date_of_birth' => "1979-06-09",
            'location'      => "London",
            'created_at'    => "2018-12-10 21:00:00",
            'updated_at'    => "2018-12-10 21:00:00",

        ]);

    }
}
