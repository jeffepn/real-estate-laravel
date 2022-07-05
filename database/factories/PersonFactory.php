<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Person\TypePerson;

$factory->define(Person::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'type_person_id' => function () {
            return factory(TypePerson::class)->create()->id;
        },
        'bio' => $faker->sentence(4),
    ];
});
