<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupUserSeeder extends Seeder
{
    // array with the position in table where a user has a membership or subscriptions towards a group
    const memberships   = [[2, 2], [3, 4], [6, 8], [9, 3]];
    const subscriptions = [[1, 10], [4, 6], [5, 7], [7, 1], [8, 5], [10, 9]];

    public function run()
    {

        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate as much entries in the table as memberships stored in the membership-array
        for ($i = 0; $i < count(self::memberships); $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            // insert data to 'group_user' table of database with the status 'membership'
            DB::table('group_user')->insert([

                'group_id'   => self::memberships[$i][0],
                'user_id'    => self::memberships[$i][1],
                'status'     => "membership",
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }

        // generate as much entries in the table as subscriptions stored in the subscription-array
        for ($i = 0; $i < count(self::subscriptions); $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            // insert data to 'group_user' table of database with the status 'subscription'
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