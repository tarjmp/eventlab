<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventRepliesSeeder extends Seeder
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

        for ($i = 0; $i < 10; $i++) {


            $date = $faker->date($format = 'Y-m-d', $max = 'now');
            $time = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));

            $status = ["accepted", "rejected"];

            $randomEvent = rand(1, 10);


            $contentTable = DB::table('event_replies')->where('event_id', $randomEvent)->pluck('user_id');

               while(!empty($contentTable[0])) {

                   $randomEvent = rand(1, 10);
                   $contentTable = DB::table('event_replies')->where('event_id', $randomEvent)->pluck('user_id');
                }


                $groupID = DB::table('events')->where('id', $randomEvent)->pluck('group_id');

                if ($groupID->first()== NULL) {
                    $userID = DB::table('events')->where('id', $randomEvent)->pluck('created_by');
                } else {
                    $userID = DB::table('group_user')->where('group_id', $groupID)->pluck('user_id');
                }

                if (count($userID) - 1 > 1) {
                    $number = rand(0, count($userID) - 1);
                } else if (count($userID) - 1 == 1) {
                    $number = 1;
                } else {
                    $number = 0;
                }


                DB::table('event_replies')->insert([

                    'event_id'   => $randomEvent,
                    'user_id'    => $userID[$number],
                    'status'     => $status[rand(0, 1)],
                    'created_at' => $datetime,
                    'updated_at' => $updatedDatetime,

                ]);
            }
        }
}