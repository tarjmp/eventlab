<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // DB::statement("TRUNCATE TABLE {'users'} RESTART IDENTITY CASCADE");


        $faker = Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {

            $randomGroup = rand(1,10);
            $userID = DB::table('group_user')->where('group_id', $randomGroup)->pluck('user_id');

            while(empty($userID[0])) {

                $randomGroup = rand(1, 10);
                $userID = DB::table('group_user')->where('group_id', $randomGroup)->pluck('user_id');
            }


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

                    'id'            => $i+1,
                    'name'          => $faker->sentence,
                    'description'   => $faker->sentence,
                    'location'      => $faker->city,
                    'start_time'    => $datetime,
                    'end_time'      => $updatedDatetime,
                    'all_day'       => 'false',
                    'group_id'      => $randomGroup,
                    'created_by'    => $userID[$number],
                    'created_at'    => $datetime_1,
                    'updated_at'    => $updatedDatetime_1,

                ]);
        }

        for ($i = 0; $i < 5; $i++) {

            $randomUser = rand(1,10);

            $date = $faker->date($format = 'Y-m-d', $max = 'now');
            $time = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            $date_1 = $faker->date($format = 'Y-m-d', $max = 'now');
            $time_1 = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime_1 = $date_1 . ' ' . $time_1;
            $updatedDatetime_1 = date('Y-m-d H:i:s', strtotime($datetime_1 . ' +1 day'));


            DB::table('events')->insert([

                'id'            => $i+6,
                'name'          => $faker->sentence,
                'description'   => $faker->paragraph,
                'location'      => $faker->city,
                'start_time'    => $datetime,
                'end_time'      => $updatedDatetime,
                'all_day'       => 'true',
                'group_id'      => null,
                'created_by'    => $randomUser,
                'created_at'    => $datetime_1,
                'updated_at'    => $updatedDatetime_1,

            ]);
        }
    }
}
