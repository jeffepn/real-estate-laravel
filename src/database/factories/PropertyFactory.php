<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Property;
use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Business;
use JPAddress\Models\Address\Address;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'business_id' => function () use ($faker) {
            return factory(Business::class)->create(['name' => $faker->name]);
        },
        'address_id' => function () use ($faker) {
            return Address::create(['addresses' => $faker->streetName]);
        }
    ];
});
