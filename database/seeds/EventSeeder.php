<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run()
    {
        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate entries in the table
        for ($i = 0; $i < SeedConstants::NUM_EVENTS; $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s for 'start_time' and 'end_time'
            $startEndDate            = $faker->date($format = 'Y-m-d', $max = 'now');
            $startEndTime            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of 'start_time'
            $startEndDatetime        = $startEndDate . ' ' . $startEndTime;
            $randomTime              = rand(1, 10);
            // timestamp of 'end_time'
            $startEndUpdatedDatetime = date('Y-m-d H:i:s', strtotime($startEndDatetime . " +{$randomTime} hours"));

            // generate a date and a time with faker in the format: Y-m-d H:i:s for 'created_at' and 'end_time'
            $createdUpdatedDate            = $faker->date($format = 'Y-m-d', $max = 'now');
            $createdUpdatedTime            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of 'created_at'
            $createdUpdatedDatetime        = $createdUpdatedDate . ' ' . $createdUpdatedTime;
            // timestamp of 'updated_at'
            $createdUpdatedUpdatedDatetime = date('Y-m-d H:i:s', strtotime($createdUpdatedDatetime . ' +1 day'));

            // insert data to 'events' table of database
            DB::table('events')->insert([

                'name'        => $faker->sentence,
                'description' => $faker->sentence,
                'location'    => $faker->city,
                'start_time'  => $startEndDatetime,
                'end_time'    => $startEndUpdatedDatetime,
                'all_day'     => in_array($i + 1, SeedConstants::EVENT_ALL_DAY),
                'group_id'    => SeedConstants::EVENT_CREATED_BY[$i][0],
                'created_by'  => SeedConstants::EVENT_CREATED_BY[$i][1],
                'created_at'  => $createdUpdatedDatetime,
                'updated_at'  => $createdUpdatedUpdatedDatetime,

            ]);
        }
    }
}
