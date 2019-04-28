<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{

    const bringWith = [[7, 3], [8, 10], [1, 2], [4, 5], [10, 2], [5, 2], [9, 7], [6, 4], [3, 8], [2, 4]];

    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {


            $date            = $faker->date($format = 'Y-m-d', $max = 'now');
            $time            = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));

            $randomAmount = rand(1, 10);

            DB::table('items')->insert([

                'id'         => $i + 1,
                'name'       => $faker->sentence,
                'amount'     => $randomAmount . " kg",
                'event_id'   => self::bringWith[$i][0],
                'user_id'    => self::bringWith[$i][1],
                'created_at' => $datetime,
                'updated_at' => $updatedDatetime,

            ]);
        }
    }
}