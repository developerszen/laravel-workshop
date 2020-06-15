<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Format;
use Faker\Generator as Faker;

$factory->define(Format::class, function (Faker $faker) {
    return [
        'edition' => $faker->sentence(3),
        'price'=> $faker->randomFloat(2, 200, 700),
        'image' => $faker->randomElement([null, $faker->imageUrl(400, 400)]),
    ];
});
