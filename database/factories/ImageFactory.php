<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Common\Image;
use Illuminate\Support\Str;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'way' => function () use ($faker) {
            if (config('app.env') == 'testing') return $faker->imageUrl();

            $contents = file_get_contents(
                "https://picsum.photos/seed/" . rand(1, 1000) . "/400/225"
            );
            $nameImage = config('realestatelaravel.filesystem.entities.projects.path') . '/' . Str::uuid() . '.jpg';
            return Storage::disk(
                config('realestatelaravel.filesystem.entities.projects.disk')
            )
                ->put($nameImage, $contents);
        },
        'alt' => $faker->sentence(4),
        'title' => $faker->sentence(4),
        'description' => $faker->sentence(4),
        'author' => $faker->name(),
    ];
});
