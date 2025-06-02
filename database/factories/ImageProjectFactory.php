<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Common\Image;
use Jeffpereira\RealEstate\Models\Project\ImageProject;
use Jeffpereira\RealEstate\Models\Project\Project;

class ImageProjectFactory extends Factory
{
    protected $model = ImageProject::class;

    public function definition(): array
    {
        return [
            'project_id' => fn () => Project::factory()->create()->id,
            'image_id' => fn () => Image::factory()->create()->id,
        ];
    }
}
