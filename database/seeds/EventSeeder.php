<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{

    const createdBy = [[2, 2], [3, 4], [6, 8], [NULL, 5], [2, 2], [3, 4], [9, 3], [NULL, 10], [NULL, 7], [2, 2]];
    const allDay    = [1, 4, 6, 7, 8, 10];

    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {

            $StartEndDate            = $faker->date($format = 'Y-m-d', $max = 'now');
            $StartEndTime            = $faker->time($format = 'H:i:s', $max = 'now');
            $StartEndDatetime        = $StartEndDate . ' ' . $StartEndTime;
            $randomTime              = rand(1, 10);
            $StartEndUpdatedDatetime = date('Y-m-d H:i:s', strtotime($StartEndDatetime . " +{$randomTime} hours"));


            $CreatedUpdatedDate            = $faker->date($format = 'Y-m-d', $max = 'now');
            $CreatedUpdatedTime            = $faker->time($format = 'H:i:s', $max = 'now');
            $CreatedUpdatedDatetime        = $CreatedUpdatedDate . ' ' . $CreatedUpdatedTime;
            $CreatedUpdatedUpdatedDatetime = date('Y-m-d H:i:s', strtotime($CreatedUpdatedDatetime . ' +1 day'));

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
