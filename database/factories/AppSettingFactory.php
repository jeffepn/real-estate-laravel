<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;
use Jeffpereira\RealEstate\Models\AppSettings;

class AppSettingFactory extends Factory
{
    protected $model = AppSettings::class;

    public function definition()
    {
        return [
            'name' => AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY,
            'value' => [
                'image' => $this->faker->imageUrl(),
            ],
        ];
    }
}
