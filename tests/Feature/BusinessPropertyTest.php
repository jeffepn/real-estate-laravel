<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
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
        $businessProperty = factory(BusinessProperty::class)->create([
            'status_situation' => BusinessPropertySituationEnum::IN_PROGRESS,
        ]);

        $this->assertFalse($businessProperty->isCompleted);
    }

    /**
     * @test
     * @group business
     * @group business-property
     */
    public function checkIfIsCompleted()
    {
        $businessProperty = factory(BusinessProperty::class)->create([
            'status_situation' => BusinessPropertySituationEnum::COMPLETED,
        ]);

        $this->assertTrue($businessProperty->isCompleted);
    }

    /**
     * @test
     * @group business
     * @group business-property
     */
    public function checkHasSituationWhenTheBusinessNotHasNameCompleted()
    {
        $businessProperty = factory(BusinessProperty::class)
            ->create(['status_situation' => BusinessPropertySituationEnum::COMPLETED]);

        $this->assertFalse($businessProperty->hasSituation);
    }

    /**
     * @test
     * @group business
     * @group business-property
     */
    public function checkHasSituationWhenBusinessHasNameCompleted()
    {
        $business = factory(Business::class)->create(['name_completed' => 'Teste name completed']);
        $businessProperty = factory(BusinessProperty::class)
            ->create(['business_id' => $business->id]);

        $this->assertEquals('Teste name completed', $businessProperty->business->name_completed);
        $this->assertTrue($businessProperty->hasSituation);
    }
}
