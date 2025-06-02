<?php

namespace Jeffpereira\RealEstate\Tests\Feature\Property;

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
     * @dataProvider dataCompleted
     */
    public function checkIfIsNotCompleted($statusSituation, $isCompleted)
    {
        $businessProperty = BusinessProperty::factory()
            ->create(['status_situation' => $statusSituation]);

        $this->assertEquals($isCompleted, $businessProperty->isCompleted);
    }

    /**
     * @test
     * @group business
     * @group business-property
     */
    public function checkIfDispatchEventWhenCompleted()
    {
        Event::fake(BusinessPropertyFinalizedEvent::class);
        $property = Property::factory()
            ->create();
        $business = Business::factory()
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

    /**
     * @test
     * @group business
     * @group business-property
     * @dataProvider dataDiscounted
     */
    public function checkIfHasDiscount($oldValue, $value, $isDicounted)
    {
        $businessProperty = BusinessProperty::factory()
            ->create([
                'old_value' => $oldValue,
                'value' => $value,
            ]);

        $this->assertEquals($isDicounted, $businessProperty->isDiscounted);
    }

    public static function dataCompleted(): array
    {
        return [
            'When status_situation is complete' => [BusinessPropertySituationEnum::COMPLETED, true],
            'When status_situation is in progress' => [BusinessPropertySituationEnum::IN_PROGRESS, false],
        ];
    }

    public static function dataDiscounted(): array
    {
        $value = rand(10, 9999);

        return [
            'Old value greater than value - true' => [rand($value + 1, 99999), $value, true],
            'Old value equal than value - false' => [$value, $value, false],
            'Old value less than value - false' => [$value - 1, $value, false],
            'Old value not provided - false' => [null, $value, false],
            'Value not provided - false' => [$value, null, false],
            'Old value and value not provided - false' => [null, null, false],
        ];
    }
}
