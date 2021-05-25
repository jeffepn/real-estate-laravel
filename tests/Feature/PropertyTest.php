<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

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
    public function it()
    {
        // $this->assertTrue(true);
    }
}
