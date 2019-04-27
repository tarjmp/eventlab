<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

      /*  $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(GroupUserSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(EventRepliesSeeder::class);
        $this->call(ItemSeeder::class); */
        $this->call(MessageSeeder::class);




    }
}
