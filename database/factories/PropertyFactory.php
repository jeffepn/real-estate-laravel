<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use JPAddress\Models\Address\Address;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'situation_id' => fn () => $this->faker->boolean()
                ? Situation::factory()->create()->id
                : null,
            'address_id' => fn () => Address::factory()->create()->id,
            'sub_type_id' => fn () => SubType::factory()->create()->id,
            'min_description' => $this->faker->sentence(4, true),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }
}
