<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Type;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Type::class, function (Faker $faker) {
    return [
        'name' => Str::limit($faker->unique()->name(), 30, '')
    ];
});
