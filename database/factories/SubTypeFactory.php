<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\SubType;
use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Type;
use Illuminate\Support\Str;

$factory->define(SubType::class, function (Faker $faker) {
    return [
        'name' => Str::limit($faker->unique()->name(), 30, ''),
        'type_id' => function () {
            return factory(Type::class)->create()->id;
        },
    ];
});
