<?php

use App\Author;
use App\Book;
use App\Category;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
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
        $authors = Author::all();
        $categories = Category::all();

        factory(Book::class, 10)->make()->each(function ($book) use ($users, $authors, $categories, $faker) {
            $book->fk_created_by = $users->random()->id;
            $book->fk_updated_by = $faker->randomElement([null, $users->random()->id]);
            $book->save();

            $book->authors()->attach([$authors->random()->id, $authors->random()->id]);
            $book->categories()->attach([$categories->random()->id, $categories->random()->id]);
        });
    }
}
