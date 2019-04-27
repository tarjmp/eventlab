<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
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
            $updatedDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 day'));

            $randomEvent = rand(1, 10);


            $contentTable = DB::table('items')->where('event_id', $randomEvent)->pluck('user_id');

            while(!empty($contentTable[0])) {

                $randomEvent = rand(1, 10);
                $contentTable = DB::table('items')->where('event_id', $randomEvent)->pluck('user_id');
            }


            $groupID = DB::table('events')->where('id', $randomEvent)->pluck('group_id');

            if ($groupID->first()== NULL) {
                $userID = DB::table('events')->where('id', $randomEvent)->pluck('created_by');
            } else {
                $userID = DB::table('group_user')->where('group_id', $groupID)->pluck('user_id');
            }

            if (count($userID) - 1 > 1) {
                $number = rand(0, count($userID) - 1);
            } else if (count($userID) - 1 == 1) {
                $number = 1;
            } else {
                $number = 0;
            }

            DB::table('items')->insert([

                'id'            => $i+1,
                'name'          => $faker->sentence,
                'amount'        => $faker->sentence,
                'event_id'      => $randomEvent,
                'user_id'       => $userID[$number],
                'created_at'    => $datetime,
                'updated_at'    => $updatedDatetime,

            ]);
        }
    }
}