<?php

use Faker\Factory;
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

            'email'         => "john.doe@gmail.com",
            'password'      => bcrypt("secret"),
            'first_name'    => "John",
            'last_name'     => "Doe",
            'date_of_birth' => "1979-06-09",
            'location'      => "London",
            'created_at'    => "2018-12-10 21:00:00",
            'updated_at'    => "2018-12-10 21:00:00",

        ]);

 /*       $faker = Faker\Factory::create();

        $date = $faker->date($format = 'Y-m-d', $max = 'now');
        $time = $faker->time($format = 'H:i:s', $max = 'now');
        $datetime = $date . ' ' . $time;


           DB::table('group_user')->insert([

                'group_id'      => 10,
                'user_id'       => 2,
                'status'        => "membership",
                'created_at'    => $datetime,
                'updated_at'    => $datetime,


        ]);*/

   /*     $faker = Faker\Factory::create();

        $randomGroup = rand(1,10);

        $userID = DB::table('group_user')->where('group_id', $randomGroup)->pluck('user_id');

        $date = $faker->date($format = 'Y-m-d', $max = 'now');
        $time = $faker->time($format = 'H:i:s', $max = 'now');
        $datetime = $date . ' ' . $time;
        $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . " +{$randomGroup} hours"));


        $date_1 = $faker->date($format = 'Y-m-d', $max = 'now');
        $time_1 = $faker->time($format = 'H:i:s', $max = 'now');
        $datetime_1 = $date_1 . ' ' . $time_1;
        $updatedDatetime_1 = date('Y-m-d H:i:s', strtotime($datetime_1 . ' +1 day'));


        if(count($userID)-1 > 1) {
            $number = rand(0, count($userID) - 1);
        }
        else if (count($userID)-1 == 1)
        { $number = 1;}
        else {$number = 0;}

        DB::table('events')->insert([

            'id'            => 10,
            'name'          => $faker->sentence,
            'description'   => $faker->paragraph,
            'location'      => $faker->city,
            'start_time'    => $datetime,
            'end_time'      => $updatedDatetime,
            'all_day'       => 'false',
            'group_id'      => $randomGroup,
            'created_by'    => $userID[$number],
            'created_at'    => $datetime_1,
            'updated_at'    => $updatedDatetime_1,

        ]); */
    }
}
