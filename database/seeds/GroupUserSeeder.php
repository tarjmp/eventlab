<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupUserSeeder extends Seeder
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

            $randomGroup[0] = rand(1, 10);
            $randomGroup[1] = rand(1, 10);

            while($randomGroup[0] == $randomGroup[1]) {
                $randomGroup[1] = rand(1, 10);
            }

            DB::table('group_user')->insert([

                'group_id'      => $randomGroup[0],
                'user_id'       => $randomGroup[1],
                'status'        => "membership",
                'created_at'    => $datetime,
                'updated_at'    => $datetime,

            ]);
        }
    }
}