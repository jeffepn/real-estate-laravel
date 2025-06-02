<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Property\Type;
use Illuminate\Support\Str;

class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition(): array
    {
        return [
            'name' => Str::limit($this->faker->unique()->name(), 30, ''),
        ];
    }
}
