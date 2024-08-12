<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Business;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Business::class, function (Faker $faker) {
    return [
        'name' => Str::limit($faker->unique()->name(), 30, ''),
    ];
});

$factory->state(Business::class, 'sale', [
    'name' => 'venda',
]);

$factory->state(Business::class, 'rent', [
    'name' => 'aluguel',
]);
