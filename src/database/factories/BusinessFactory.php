<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Business;
use Faker\Generator as Faker;

$factory->define(Business::class, function (Faker $faker) {
    $businesses = ["Venda", "Locação", "Permuta"];

    return [
        'name' => $businesses[rand(0, 2)]
    ];
});
