<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\SubType;
use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Type;

$factory->define(SubType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'type_id' => function () {
            return factory(Type::class)->create()->id;
        },
    ];
});
