<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Jeffpereira\RealEstate\Models\Common\Image;
use Jeffpereira\RealEstate\Models\Project\ImageProject;
use Jeffpereira\RealEstate\Models\Project\Project;

$factory->define(ImageProject::class, function (Faker $faker) {
    return [
        'project_id' => function () {
            return factory(Project::class)->create()->id;
        },
        'image_id' => function () {
            return factory(Image::class)->create()->id;
        },
    ];
});
