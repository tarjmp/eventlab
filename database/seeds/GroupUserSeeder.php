<?php

use App\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupUserSeeder extends Seeder
{
    public function run()
    {
        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate as much entries in the table as memberships stored in the membership-array
        for ($i = 0; $i < count(SeedConstants::MEMBERSHIPS); $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            // insert data to 'group_user' table of database with the status 'membership'
            DB::table('group_user')->insert([

                'group_id'   => SeedConstants::MEMBERSHIPS[$i][0],
                'user_id'    => SeedConstants::MEMBERSHIPS[$i][1],
                'status'     => Group::TYPE_MEMBERSHIP,
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }

        // generate as much entries in the table as subscriptions stored in the subscription-array
        for ($i = 0; $i < count(SeedConstants::SUBSCRIPTIONS); $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            // insert data to 'group_user' table of database with the status 'subscription'
            DB::table('group_user')->insert([

                'group_id'   => SeedConstants::SUBSCRIPTIONS[$i][0],
                'user_id'    => SeedConstants::SUBSCRIPTIONS[$i][1],
                'status'     => Group::TYPE_SUBSCRIPTION,
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }
    }
}