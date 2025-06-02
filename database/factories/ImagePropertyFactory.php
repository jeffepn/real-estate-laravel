<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

class ImagePropertyFactory extends Factory
{
    protected $model = ImageProperty::class;

    public function definition(): array
    {
        return [
            'property_id' => fn () => Property::factory()->create()->id,
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
            'thumbnail' => function () {
                if (config('app.env') == 'testing') {
                    return $this->faker->imageUrl();
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
            'alt' => $this->faker->sentence(4),
            'order' => $this->faker->randomDigit(),
        ];
    }
}
