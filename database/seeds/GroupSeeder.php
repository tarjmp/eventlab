<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{

    const temporaryGroups = [1, 4, 5, 8];
    const publicGroups    = [2, 4, 7, 8, 9];

    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {

            $date            = $faker->date($format = 'Y-m-d', 'now');
            $time            = $faker->time($format = 'H:i:s', 'now');
            $datetime        = $date . ' ' . $time;
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));


            DB::table('groups')->insert([

                'id'          => $i + 1,
                'name'        => $faker->sentence,
                'description' => $faker->sentence,
                'temporary'   => in_array($i + 1, self::temporaryGroups),
                'public'      => in_array($i + 1, self::publicGroups),
                'created_at'  => $datetime,
                'updated_at'  => $updatedDatetime,

            ]);
        }
    }
}
