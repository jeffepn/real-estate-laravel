<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Person\TypePerson;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type_person_id' => fn () => TypePerson::factory()->create()->id,
            'bio' => $this->faker->sentence(4),
        ];
    }
}
