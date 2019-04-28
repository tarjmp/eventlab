<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {

            $date            = $faker->date($format = 'Y-m-d', 'now');
            $time            = $faker->time($format = 'H:i:s', 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            DB::table('users')->insert([

                'id'            => $i + 2,
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
