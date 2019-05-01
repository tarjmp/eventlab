<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        // clear all tables from database
        DB::statement("TRUNCATE TABLE messages, items, events, groups, users, event_replies, group_user RESTART IDENTITY;");

        // call all classes to insert data to database
        $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(GroupUserSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(EventRepliesSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(MessageSeeder::class);

    }
}
