<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Jeffpereira\RealEstate\Models\Property\Property;
use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use JPAddress\Models\Address\Address;
use JPAddress\Models\Address\City;
use JPAddress\Models\Address\Country;
use JPAddress\Models\Address\Neighborhood;
use JPAddress\Models\Address\State;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'situation_id' => function () use ($faker) {
            return $faker->boolean() ?
                factory(Situation::class)->create()->id
                : null;
        },
        'address_id' => function () {
            return factory(Address::class)->create([
                'cep' => '99999999',
                'neighborhood_id' => factory(Neighborhood::class)->create([
                    'city_id' => factory(City::class)->create([
                        'state_id' => factory(State::class)->create([
                            'country_id' => Country::firstOrCreate(['name' => 'brasil'])->id
                        ])
                    ])->id
                ])->id
            ]);
        },
        'sub_type_id' => function () {
            return factory(SubType::class)->create()->id;
        },
        'min_description' => $faker->sentence(4, true)
    ];
});
