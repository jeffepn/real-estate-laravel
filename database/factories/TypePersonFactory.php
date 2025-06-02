<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Person\TypePerson;

class TypePersonFactory extends Factory
{
    protected $model = TypePerson::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
        ];
    }
}
