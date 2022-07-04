<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Illuminate\Support\Str;

$factory->define(Situation::class, function (Faker $faker) {
    return [
        'name' => Str::limit($faker->unique()->name(), 30, ''),
    ];
});
