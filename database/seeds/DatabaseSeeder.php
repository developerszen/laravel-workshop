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
         $this->call([
             UserSeeder::class,
             AuthorSeeder::class,
             CategorySeeder::class,
             EditorialSeeder::class,
             BookSeeder::class,
             FormatSeeder::class,
         ]);
    }
}
