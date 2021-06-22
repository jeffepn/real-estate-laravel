<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class BusinessTest extends TestCase
{
    use RefreshDatabase;
    protected $api = 'business';

    /**
     * @test
     */
    public function verify_format_return_index()
    {
        factory(Business::class)->create();
        $response = $this->getJson($this->api);
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
        $response = $this->getJson("$this->api/$business->id");
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
        $response = $this->postJson($this->api, ['name' => 'Test of name']);
        $response->assertStatus(201);
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
        $response = $this->patchJson("$this->api/$business->id", ['name' => 'Test of name2']);
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
        $response = $this->deleteJson("$this->api/$business->id");
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
    //     $response = $this->deleteJson("$this->api/$business->id");
    //     $response->assertStatus(400);
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

        $response = $this->postJson($this->api, ['name' => Str::random(30)]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson($this->api, ['name' => Str::random(31)]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson($this->api, ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson($this->api, ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson("$this->api/$business->id", ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_OK);
    }
}
