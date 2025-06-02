<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Property\Business;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        return [
            'name' => Str::limit($this->faker->unique()->name(), 30, ''),
        ];
    }

    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'venda',
        ]);
    }

    public function rent(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'aluguel',
        ]);
    }
}
