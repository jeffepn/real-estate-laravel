<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\SubType;
use Faker\Generator as Faker;

$factory->define(SubType::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
