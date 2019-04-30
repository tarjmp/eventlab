<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    public function run()
    {
        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate entries in the table
        for ($i = 0; $i < SeedConstants::NUM_GROUPS; $i++) {

            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', 'now');
            $time            = $faker->time($format = 'H:i:s', 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));

            // insert data to 'groups' table of database
            DB::table('groups')->insert([

                'id'          => $i + 1,
                'name'        => $faker->sentence,
                'description' => $faker->sentence,
                'temporary'   => in_array($i + 1, SeedConstants::GROUPS_TEMPORARY),
                'public'      => in_array($i + 1, SeedConstants::GROUPS_PUBLIC),
                'created_at'  => $datetime,
                'updated_at'  => $updatedDatetime,

            ]);
        }
    }
}
