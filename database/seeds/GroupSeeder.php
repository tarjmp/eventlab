<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // DB::statement("TRUNCATE TABLE {'users'} RESTART IDENTITY CASCADE");


        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {


            $date = $faker->date($format = 'Y-m-d', $max = 'now');
            $time = $faker->time($format = 'H:i:s', $max = 'now');
            $datetime = $date . ' ' . $time;
            $random = rand(0, 1) > 0.5;

            DB::table('groups')->insert([

                'id'            => $i+1,
                'name'          => $faker->sentence,
                'description'   => $faker->sentence,
                'temporary'     => $random,
                'public'        => $random,
                'created_at'    => $datetime,
                'updated_at'    => $datetime,

            ]);
        }
    }
}
