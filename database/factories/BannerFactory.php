<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Banner;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        $image = rand(1, 5);
        $title = $this->faker->boolean() ? $this->faker->sentence(4, true) : null;

        return [
            'way' => "dev/images/i{$image}.jpg",
            'title' => $title,
            'content' => $title ? $this->faker->sentence(8, true) : null,
            'link' => $this->faker->boolean() ? $this->faker->url : null,
        ];
    }
}
