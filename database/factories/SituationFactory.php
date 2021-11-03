<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Situation;

$factory->define(Situation::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name()
    ];
});
