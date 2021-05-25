<?php

namespace Jeffpereira\RealEstate\Tests\Unit;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Http\Requests\Property\BusinessRequest;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_slug_can_be_defined_per_script_or_custom_text()
    {
        dd(env('KOOL_DATABASE_PORT'));

        $property = factory(Property::class)->create();
        $this->assertNotNull($property->slug);
        $this->assertEquals('', $property->slug);
    }
}
