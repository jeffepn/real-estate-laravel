<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Person\TypePerson;

$factory->define(TypePerson::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name()
    ];
});
