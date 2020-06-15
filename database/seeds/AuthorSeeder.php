<?php

use App\Author;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AuthorSeeder extends Seeder
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

        factory(Author::class, 10)->make()->each(function ($author) use ($users, $faker) {
            $author->fk_created_by = $users->random()->id;
            $author->fk_updated_by = $faker->randomElement([null, $users->random()->id]);
            $author->save();
        });
    }
}
