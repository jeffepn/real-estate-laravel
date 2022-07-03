<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Tests\TestCase;

class BusinessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function verify_format_return_index()
    {
        factory(Business::class)->create();
        $response = $this->getJson(route('jp_realestate.api.business.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['type', 'id', 'attributes' => ['slug', 'name']]]
        ], $response->json());
    }

    /**
     * @test
     */
    public function verify_format_return_show()
    {
        $business = factory(Business::class)->create();
        $response = $this->getJson(route('jp_realestate.api.business.show', $business->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                'type', 'id', 'attributes' => ['slug', 'name']
            ]
        ], $response->json());
        $this->assertEquals([
            'type' => 'business',
            'id' => $business->id,
            'attributes' => [
                'slug' => $business->slug,
                'name' => Str::title($business->name),
            ]
        ], $response->json()['data']);
    }
    /**
     * @test
     */
    public function store_with_success()
    {
        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => 'Test of name']);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => ['type', 'id', 'attributes' => ['slug', 'name']], 'error', 'message'
        ]);
        $this->assertEquals("test-of-name", Business::first()->slug);
        $this->assertEquals("TEST OF NAME", Business::first()->name);
    }

    /**
     * @test
     */
    public function update_with_success()
    {
        $business = factory(Business::class)->create();
        $response = $this->patchJson(
            route('jp_realestate.api.business.update', $business->id),
            ['name' => 'Test of name2']
        );
        $response->assertStatus(200);
        $this->assertEquals("test-of-name2", Business::first()->slug);
    }

    /**
     * @test
     */
    public function destroy_with_success()
    {
        $business = factory(Business::class)->create();
        $this->assertNotNull(Business::first());
        $response = $this->deleteJson(route('jp_realestate.api.business.destroy', $business->id));
        $response->assertStatus(200);
        $this->assertNull(Business::first());
    }

    /**
     * @test
     */
    // public function dont_destroy_business_with_one_or_more_properties()
    // {
    //     $business = factory(Business::class)->create();
    //     $this->assertNotNull(Business::first());
    //     $business->properties()->save(factory(Property::class)->make());
    //     $response = $this->deleteJson("route('jp_realestate.api.business.index')/$business->id");
    //     $response->assertStatus(Response::HTTP_BAD_REQUEST);
    //     $this->assertNotNull(Business::first());
    //     $this->assertEquals(Terminologies::get('all.business.not_delete_with_relations'), $response->json()['message']);
    // }

    /**
     * @test
     */
    public function validate_name_request()
    {
        // $request = new BusinessRequest();
        $business = factory(Business::class)->create(['name' => 'teste']);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => Str::random(30)]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => Str::random(31)]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('jp_realestate.api.business.store'), ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson(route('jp_realestate.api.business.update', $business->id), ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_OK);
    }

    // Scopes

    /**
     * @test
     * @group business
     * @group scope
     * @group business-scope-properties
     */
    public function hasProperties()
    {
        factory(Business::class, 10)->create();
        $this->assertCount(10, Business::all());
        $this->assertCount(0, Business::hasProperties()->get());
        Business::all()->each(function ($business, $key) {
            $business->properties()->attach(
                $key < 5
                    ? factory(Property::class)->state('active')->create()->id
                    : factory(Property::class)->state('inactive')->create()->id
            );
        });
        $this->assertCount(5, Business::hasProperties()->get());
        $this->assertCount(10, Property::all());
    }
}
