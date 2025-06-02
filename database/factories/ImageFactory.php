<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Common\Image;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'way' => function () {
                if (config('app.env') == 'testing') {
                    return $this->faker->imageUrl();
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
            'alt' => $this->faker->sentence(4),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(4),
            'author' => $this->faker->name(),
        ];
    }
}
