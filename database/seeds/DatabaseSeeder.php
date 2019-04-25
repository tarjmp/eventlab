<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(
    {
        //$table = 'users';
        //B::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");

       // DB::statement("ALTER {$table} DISABLE TRIGGER ALL;");

        $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);

       // DB::statement("ALTER {$table} DISABLE TRIGGER ALL;");

    }
}
