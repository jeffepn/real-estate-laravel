<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Illuminate\Support\Str;

class SubTypeFactory extends Factory
{
    protected $model = SubType::class;

    public function definition(): array
    {
        return [
            'name' => Str::limit($this->faker->unique()->name(), 30, ''),
            'type_id' => fn () => Type::factory()->create()->id,
        ];
    }
}
