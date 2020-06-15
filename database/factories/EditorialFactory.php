<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Editorial;
use Faker\Generator as Faker;

$factory->define(Editorial::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
        'image' => $faker->randomElement([null, $faker->imageUrl(400, 400)]),
    ];
});
