<?php

use App\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventRepliesSeeder extends Seeder
{
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
            if (in_array($i + 1, SeedConstants::EVENT_ACCEPTED)) {
                $status = Event::STATUS_ACCEPTED;
            } elseif (in_array($i + 1, SeedConstants::EVENT_REJECTED)) {
                $status = Event::STATUS_REJECTED;
            } else {
                $status = Event::STATUS_TENTATIVE;
            }

            // insert data to 'event_replies' table of database
            DB::table('event_replies')->insert([

                'event_id'   => SeedConstants::EVENT_REPLIES[$i][0],
                'user_id'    => SeedConstants::EVENT_REPLIES[$i][1],
                'status'     => $status,
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);


        }
    }
}