<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{

    const sendBy = [[5, 2], [3, 8], [10, 2], [8, 10], [7, 3], [2, 4], [9, 7], [6, 4], [1, 2], [4, 5]];

    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {


            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            DB::table('messages')->insert([

                'id'         => $i + 1,
                'text'       => $faker->sentence,
                'event_id'   => self::sendBy[$i][0],
                'user_id'    => self::sendBy[$i][1],
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }
    }
}