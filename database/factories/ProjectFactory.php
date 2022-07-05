<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Project\Project;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name(),
        'content' => $faker->sentence(rand(40, 100)),
        'person_id' => function () {
            return factory(Person::class)->create()->id;
        },
    ];
});
