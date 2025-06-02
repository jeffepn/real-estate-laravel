<?php

namespace Jeffpereira\RealEstate\Tests\Unit;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Tests\TestCase;

class BusinessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function slugIsDefinedPerNameOfBusiness()
    {
        $business = Business::factory()->create(['name' => 'Nome teste de negócio']);
        $this->assertEquals('nome-teste-de-negocio', $business->slug);
    }

    /**
     * @test
     */
    public function isNameOfUniqueMustBeUnique()
    {
        $business = Business::factory()->create(['name' => 'Nome teste de negócio']);
        $this->assertEquals('nome-teste-de-negocio', $business->slug);
        $codeError = null;

        try {
            $business = Business::factory()->create(['name' => 'Nome teste de negócio']);
            $this->assertEquals('nome-teste-de-negocio', $business->slug);
        } catch (QueryException $ex) {
            $codeError = $ex->getCode();
        }
        $this->assertEquals('23000', $codeError);
    }

    /**
     * @test
     */
    // public function aBusinessCanHaveOneOrMoreProperties()
    // {
    //     $business = Business::factory()->create();
    //     $business->properties()->save(Property::factory()->make());
    //     $this->assertEquals(1, $business->properties->count());
    //     $business->properties()->saveMany(Property::factory( 20)->make());
    //     $this->assertEquals(21, $business->refresh()->properties->count());
    // }
}
