<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // creating factory to use php faker
        $faker = Faker\Factory::create();

        // generate entries in the table
        for ($i = 0; $i < SeedConstants::NUM_ITEMS; $i++) {


            // generate a date and a time with faker in the format: Y-m-d H:i:s
            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            // timestamp of creation
            $datetime        = $date . ' ' . $time;
            // timestamp of update
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));

            // generate random number for the amount of an item
            $randomAmount = rand(1, 10);

            // insert data to 'items' table of database
            DB::table('items')->insert([

                'name'       => $faker->sentence,
                'amount'     => $randomAmount . " kg",
                'event_id'   => SeedConstants::ITEMS[$i][0],
                'user_id'    => SeedConstants::ITEMS[$i][1],
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }
    }
}