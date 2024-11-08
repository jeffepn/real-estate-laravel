<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

$factory->define(ImageProperty::class, function (Faker $faker) {
    return [
        'property_id' => function () {
            return factory(Property::class)
                ->create()->id;
        },
        'way' => function () use ($faker) {
            if (config('app.env') == 'testing') {
                return $faker->imageUrl();
            }

            $contents = file_get_contents(
                'https://picsum.photos/seed/' . rand(1, 1000) . '/400/225'
            );
            $nameImage = ConfigHelper::get('filesystem.entities.projects.path') . '/' . Str::uuid() . '.jpg';

            return Storage::disk(
                ConfigHelper::get('filesystem.entities.projects.disk')
            )
                ->put($nameImage, $contents);
        },
        'thumbnail' => function () use ($faker) {
            if (config('app.env') == 'testing') {
                return $faker->imageUrl();
            }

            $contents = file_get_contents(
                'https://picsum.photos/seed/' . rand(1, 1000) . '/100/70'
            );
            $nameImage = ConfigHelper::get('filesystem.entities.projects.path') . '/' . Str::uuid() . '.jpg';

            return Storage::disk(
                ConfigHelper::get('filesystem.entities.projects.disk')
            )
                ->put($nameImage, $contents);
        },
        'alt' => $faker->sentence(4),
        'order' => $faker->randomDigit(),
    ];
});
