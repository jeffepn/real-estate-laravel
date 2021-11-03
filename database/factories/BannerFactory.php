<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Banner;
use Faker\Generator as Faker;

$factory->define(Banner::class, function (Faker $faker) {
    $image = rand(1, 5);
    $title = $faker->boolean() ? $faker->sentence(4, true) : null;
    return [
        'way' => "dev/images/i{$image}.jpg",
        'title' => $title,
        'content' => $title ? $faker->sentence(8, true) : null,
        'link' => $faker->boolean() ? $faker->url : null,
    ];
});
