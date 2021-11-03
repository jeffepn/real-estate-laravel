<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Business;
use Faker\Generator as Faker;

$factory->define(Business::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name()
    ];
});
