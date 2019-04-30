<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate entries in the table
        for ($i = 0; $i < SeedConstants::NUM_USERS; $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', 'now');
            $time            = $faker->time($format = 'H:i:s', 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            // insert data to 'users' table of database
            DB::table('users')->insert([

                'email'         => $faker->email,
                'password'      => bcrypt('secret'),
                'first_name'    => $faker->firstName(Null),
                'last_name'     => $faker->lastName,
                'date_of_birth' => $faker->date($format = 'Y-m-d', 'now'),
                'location'      => $faker->city,
                'created_at'    => $datetime,
                'updated_at'    => $updatedDatetime,

            ]);
        }
    }
}
