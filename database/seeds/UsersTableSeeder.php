<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*     DB::table('users')->insert([
                 'name'     => str_random(10),
                 'email'    => str_random(10) . '@gmail.com',
                 'password' => bcrypt('secret'),
             ]); */

        // reset the user table
    //    DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // generate a user in the table
        DB::table('users')->insert([
            'email'         => "john.doe@gmail.com",
            'password'      => bcrypt("secret"),
            'first_name'    => "John",
            'last_name'     => "Doe",
            'date_of_birth' => "1979-06-09",
            'location'      => "London",
        ]);
    }
}
