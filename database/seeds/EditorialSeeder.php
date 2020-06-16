<?php

use App\Editorial;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EditorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $faker = Faker::create();

        factory(Editorial::class, 10)->make()->each(function ($editorial) use ($users, $faker) {
            $editorial->fk_created_by = $users->random()->id;
            $editorial->fk_updated_by = $faker->randomElement([null, $users->random()->id]);
            $editorial->save();
        });
    }
}
