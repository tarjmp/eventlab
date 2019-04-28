<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventRepliesSeeder extends Seeder
{

    // array with the relation between an event and an user
    const replyFrom       = [[1, 2], [4, 5], [3, 8], [2, 4], [7, 3], [8, 10], [6, 4], [9, 7], [5, 2], [10, 2]];

    // array with the position in table where a reply was accepted, rejected or tentative
    const statusAccepted  = [1, 4, 7];
    const statusRejected  = [2, 10];
    const statusTentative = [3, 5, 6, 8, 9];

    public function run()
    {

        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate 10 entries in the table
        for ($i = 0; $i < 10; $i++) {


            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            // inquiry if event was accepted, rejected or tentative
            if (in_array($i + 1, self::statusAccepted)) {
                $status = "accepted";
            } elseif (in_array($i + 1, self::statusRejected)) {
                $status = "rejected";
            } else {
                $status = "tentative";
            }

            // insert data to 'event_replies' table of database
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