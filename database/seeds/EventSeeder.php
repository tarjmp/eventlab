<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    // array with the relation between a group and an user
    const createdBy = [[2, 2], [3, 4], [6, 8], [NULL, 5], [2, 2], [3, 4], [9, 3], [NULL, 10], [NULL, 7], [2, 2]];

    // array with the position where a event is an all day event
    const allDay    = [1, 4, 6, 7, 8, 10];

    public function run()
    {
        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate 10 entries in the table
        for ($i = 0; $i < 10; $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s for 'start_time' and 'end_time'
            $StartEndDate            = $faker->date($format = 'Y-m-d', $max = 'now');
            $StartEndTime            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of 'start_time'
            $StartEndDatetime        = $StartEndDate . ' ' . $StartEndTime;
            $randomTime              = rand(1, 10);
            // timestamp of 'end_time'
            $StartEndUpdatedDatetime = date('Y-m-d H:i:s', strtotime($StartEndDatetime . " +{$randomTime} hours"));

            // generate a date and a time with faker in the format: Y-m-d H:i:s for 'created_at' and 'end_time'
            $CreatedUpdatedDate            = $faker->date($format = 'Y-m-d', $max = 'now');
            $CreatedUpdatedTime            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of 'created_at'
            $CreatedUpdatedDatetime        = $CreatedUpdatedDate . ' ' . $CreatedUpdatedTime;
            // timestamp of 'updated_at'
            $CreatedUpdatedUpdatedDatetime = date('Y-m-d H:i:s', strtotime($CreatedUpdatedDatetime . ' +1 day'));

            // insert data to 'events' table of database
            DB::table('events')->insert([

                'id'          => $i + 1,
                'name'        => $faker->sentence,
                'description' => $faker->sentence,
                'location'    => $faker->city,
                'start_time'  => $StartEndDatetime,
                'end_time'    => $StartEndUpdatedDatetime,
                'all_day'     => in_array($i + 1, self::allDay),
                'group_id'    => self::createdBy[$i][0],
                'created_by'  => self::createdBy[$i][1],
                'created_at'  => $CreatedUpdatedDatetime,
                'updated_at'  => $CreatedUpdatedUpdatedDatetime,

            ]);
        }
    }
}
