<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Events\BusinessPropertyFinalizedEvent;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Tests\TestCase;

class BusinessPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group business
     * @group business-property
     */
    public function checkIfIsNotCompleted()
    {
        $businessProperty = factory(BusinessProperty::class)
            ->create(['status_situation' => BusinessPropertySituationEnum::IN_PROGRESS]);

        $this->assertFalse($businessProperty->isCompleted);
    }

    /**
     * @test
     * @group business
     * @group business-property
     */
    public function checkIfIsCompleted()
    {
        Event::fake(BusinessPropertyFinalizedEvent::class);
        $property = factory(Property::class)
            ->create();
        $business = factory(Business::class)
            ->create();
        $businessProperty = BusinessProperty::create([
            'property_id' => $property->id,
            'business_id' => $business->id,
            'value' => rand(10, 1000),
            'status_situation' => BusinessPropertySituationEnum::IN_PROGRESS,
        ]);
        $businessProperty->update(['status_situation' => BusinessPropertySituationEnum::COMPLETED]);

        Event::assertDispatched(BusinessPropertyFinalizedEvent::class);
        $this->assertTrue($businessProperty->isCompleted);
    }
}
