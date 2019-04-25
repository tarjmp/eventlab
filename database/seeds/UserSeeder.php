<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       // DB::statement("TRUNCATE TABLE {'users'} RESTART IDENTITY CASCADE");


        $users = [];
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {


            $date = $faker->date($format = 'Y-m-d', $max = 'now');
            $time = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime = $date . ' ' . $time;
            $users[] = [

                DB::table('users')->insert([
                'email'         => $faker->email,
                'password'      => bcrypt($faker->password),
                'first_name'    => $faker->firstName($gender = Null),
                'last_name'     => $faker->lastName,
                'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'location'      => $faker->city,
                'created_at'    => $datetime,
                'updated_at'    => $datetime,

                ])
            ];
        }
    }
}
