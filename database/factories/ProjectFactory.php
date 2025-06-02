<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Project\Project;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'content' => $this->faker->sentence(rand(40, 100)),
            'person_id' => fn () => Person::factory()->create()->id,
        ];
    }
}
