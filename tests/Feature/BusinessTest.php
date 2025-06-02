<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class BusinessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group business
     */
    public function verify_format_return_index()
    {
        Business::factory()->create();
        $response = $this->getJson(route('jp_realestate.api.business.index'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [['type', 'id', 'attributes' => ['slug', 'name']]],
        ], $response->json());
    }

    /**
     * @test
     * @group business
     */
    public function verify_format_return_show()
    {
        $business = Business::factory()->create(['name_completed' => 'Example name completed']);
        $response = $this->getJson(route('jp_realestate.api.business.show', $business->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'type', 'id', 'attributes' => ['slug', 'name'],
            ],
        ], $response->json());
        $this->assertEquals([
            'type' => 'business',
            'id' => $business->id,
            'attributes' => [
                'slug' => $business->slug,
                'name' => Str::title($business->name),
                'name_completed' => 'Example Name Completed',
                'has_situation' => true,
            ],
        ], $response->json()['data']);
    }

    /**
     * @test
     * @group business
     */
    public function store_with_success()
    {
        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => 'Test of name']);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => ['type', 'id', 'attributes' => ['slug', 'name']], 'error', 'message',
        ]);
        $this->assertEquals('test-of-name', Business::first()->slug);
        $this->assertEquals('TEST OF NAME', Business::first()->name);
    }

    /**
     * @test
     * @group business
     */
    public function update_with_success()
    {
        $business = Business::factory()->create();
        $response = $this->patchJson(
            route('jp_realestate.api.business.update', $business->id),
            ['name' => 'Test of name2']
        );
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('test-of-name2', Business::first()->slug);
    }

    /**
     * @test
     * @group business
     */
    public function destroy_with_success()
    {
        $business = Business::factory()->create();
        $this->assertNotNull(Business::first());
        $response = $this->deleteJson(route('jp_realestate.api.business.destroy', $business->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertNull(Business::first());
    }

    /**
     * @test
     * @group business
     */
    public function validate_name_request()
    {
        // $request = new BusinessRequest();
        $business = Business::factory()->create(['name' => 'teste']);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => Str::random(30)]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => Str::random(31)]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(
            route('jp_realestate.api.business.store'),
            ['name' => 'teste2', 'name_completed' => Str::random(31)]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson(route('jp_realestate.api.business.update', $business->id), ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @group business
     */
    public function checkHasSituationWhenTheBusinessNotHasNameCompleted()
    {
        $business = Business::factory()
            ->create();

        $this->assertFalse($business->hasSituation);
    }

    /**
     * @test
     * @group business
     */
    public function checkHasSituationWhenBusinessHasNameCompleted()
    {
        $business = Business::factory()->create(['name_completed' => 'Teste name completed']);

        $this->assertEquals('Teste name completed', $business->name_completed);
        $this->assertTrue($business->hasSituation);
    }

    /**
     * @test
     * @group business
     * @group scope
     * @group business-scope-properties
     */
    public function hasProperties()
    {
        Business::factory(10)->create();
        $this->assertCount(10, Business::all());
        $this->assertCount(0, Business::hasProperties()->get());
        Business::all()->each(function ($business, $key) {
            $business->properties()->attach(
                $key < 5
                ? Property::factory()->active()->create()->id
                : Property::factory()->inactive()->create()->id
            );
        });
        $this->assertCount(5, Business::hasProperties()->get());
        $this->assertCount(10, Property::all());
    }
}
