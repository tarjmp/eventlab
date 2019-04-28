<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupUserSeeder extends Seeder
{

    const memberships   = [[2, 2], [3, 4], [6, 8], [9, 3]];
    const subscriptions = [[1, 10], [4, 6], [5, 7], [7, 1], [8, 5], [10, 9]];

    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < count(self::memberships); $i++) {

            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            DB::table('group_user')->insert([

                'group_id'   => self::memberships[$i][0],
                'user_id'    => self::memberships[$i][1],
                'status'     => "membership",
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }

        for ($i = 0; $i < count(self::subscriptions); $i++) {

            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            DB::table('group_user')->insert([

                'group_id'   => self::subscriptions[$i][0],
                'user_id'    => self::subscriptions[$i][1],
                'status'     => "subscription",
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }
    }
}