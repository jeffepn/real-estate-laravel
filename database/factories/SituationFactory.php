<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Illuminate\Support\Str;

class SituationFactory extends Factory
{
    protected $model = Situation::class;

    public function definition(): array
    {
        return [
            'name' => Str::limit($this->faker->unique()->name(), 30, ''),
        ];
    }
}
