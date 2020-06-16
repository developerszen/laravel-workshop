<?php

use App\Book;
use App\Editorial;
use App\Format;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FormatSeeder extends Seeder
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
        $books = Book::all();
        $editorials = Editorial::all();

        factory(Format::class, 10)->make()->each(function ($format) use ($users, $books, $editorials, $faker) {
            $format->fk_created_by = $users->random()->id;
            $format->fk_updated_by = $faker->randomElement([null, $users->random()->id]);
            $format->fk_book = $books->random()->id;
            $format->fk_editorial = $editorials->random()->id;
            $format->save();
        });
    }
}
