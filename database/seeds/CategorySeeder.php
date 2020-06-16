<?php

use App\Category;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
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

        factory(Category::class, 10)->make()->each(function ($category) use ($users, $faker) {
            $category->fk_created_by = $users->random()->id;
            $category->fk_updated_by = $faker->randomElement([null, $users->random()->id]);
            $category->save();
        });
    }
}
