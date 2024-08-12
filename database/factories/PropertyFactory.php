<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Property;
use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use JPAddress\Models\Address\Address;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'situation_id' => function () use ($faker) {
            return $faker->boolean() ?
                factory(Situation::class)->create()->id
                : null;
        },
        'address_id' => function () {
            return factory(Address::class)->create()->id;
        },
        'sub_type_id' => function () {
            return factory(SubType::class)->create()->id;
        },
        'min_description' => $faker->sentence(4, true),
    ];
});

$factory->state(Property::class, 'active', function (Faker $faker) {
    return [
        'active' => true,
    ];
});

$factory->state(Property::class, 'inactive', function (Faker $faker) {
    return [
        'active' => false,
    ];
});
