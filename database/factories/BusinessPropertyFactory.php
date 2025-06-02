<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\Property;

class BusinessPropertyFactory extends Factory
{
    protected $model = BusinessProperty::class;

    public function definition(): array
    {
        return [
            'business_id' => fn () => Business::factory()->create()->id,
            'property_id' => fn () => Property::factory()->create()->id,
            'status' => 1,
            'status_situation' => BusinessPropertySituationEnum::IN_PROGRESS,
        ];
    }
}
