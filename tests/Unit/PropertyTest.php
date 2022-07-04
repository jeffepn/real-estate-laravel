<?php

namespace Jeffpereira\RealEstate\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aSlugMustBeUnique()
    {
        $property = factory(Property::class)->create();
        $codeException = null;

        try {
            factory(Property::class)->create(['slug' => $property->slug]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $codeException = $ex->getCode();
        }
        $this->assertEquals('23000', $codeException);
    }

    public function testFormatGeneratSluBasedIinContext()
    {
        $address = $this->createAddress();
        $business = factory(Business::class)->create();
        $subType = factory(SubType::class)->create();
        $property = Property::create([
            'sub_type_id' => $subType->id,
            'address_id' => $address->id, 'min_description' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magni necessitatibus non architecto adipisci quidem',
            'max_dormitory' => 3, 'max_bathroom' => 2, 'max_suite' => 1, 'max_garage' => 2,
        ]);
        $this->assertEquals(
            Str::slug(
                Str::limit(
                    sprintf(
                        '%s em %s - %s %s %s %s %s',
                        Str::title($property->sub_type->name),
                        Str::title($property->address->neighborhood->name),
                        Str::title($property->address->neighborhood->city->state->initials),
                        $property->max_dormitory ? "{$property->max_dormitory} dormitórios," : '',
                        $property->max_bathroom ? "{$property->max_bathroom} banheiros," : '',
                        $property->max_suite ? "{$property->max_suite} suites," : '',
                        $property->max_garage ? "{$property->max_garage} garagens," : '',
                    ),
                    150
                )
            ),
            $property->slug
        );
    }
}
