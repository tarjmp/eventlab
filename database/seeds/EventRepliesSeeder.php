<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventRepliesSeeder extends Seeder
{

    const replyFrom       = [[1, 2], [4, 5], [3, 8], [2, 4], [7, 3], [8, 10], [6, 4], [9, 7], [5, 2], [10, 2]];
    const statusAccepted  = [1, 4, 7];
    const statusRejected  = [2, 10];
    const statusTentative = [3, 5, 6, 8, 9];

    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {


            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            if (in_array($i + 1, self::statusAccepted)) {
                $status = "accepted";
            } elseif (in_array($i + 1, self::statusRejected)) {
                $status = "rejected";
            } else {
                $status = "tentative";
            }

            DB::table('event_replies')->insert([

                'event_id'   => self::replyFrom[$i][0],
                'user_id'    => self::replyFrom[$i][1],
                'status'     => $status,
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);


        }
    }
}