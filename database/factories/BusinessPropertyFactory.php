<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\Property;

$factory->define(BusinessProperty::class, function (Faker $faker) {
    return [
        'business_id' => function () {
            return factory(Business::class)
                ->create()
                ->id;
        },
        'property_id' => function () {
            return factory(Property::class)
                ->create()
                ->id;
        },
        'status' => 1,
        'status_situation' => BusinessPropertySituationEnum::IN_PROGRESS,
    ];
});
