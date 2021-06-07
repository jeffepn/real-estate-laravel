<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class SubTypeTest extends TestCase
{
    use RefreshDatabase;
    protected $api = '/api/sub-type';

    /**
     * @test
     */
    public function verify_format_return_index()
    {
        factory(SubType::class)->create();
        $response = $this->getJson($this->api);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [[
                'type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['type']
            ]], 'included',
        ], $response->json());
    }

    /**
     * @test
     */
    public function verify_format_return_show()
    {
        $subType = factory(SubType::class)->create();
        $response = $this->getJson("$this->api/$subType->id");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                'type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['type']
            ], 'included',
        ], $response->json());
        $this->assertEquals([
            'type' => 'sub_type',
            'id' => $subType->id,
            'attributes' => [
                'slug' => $subType->slug,
                'name' => Str::title($subType->name),
            ],
            'relationships' => [
                'type' => ['data' => ['type' => 'type', 'id' => $subType->type_id]]
            ]
        ], $response->json()['data']);
    }
    /**
     * @test
     */
    public function store_with_success()
    {
        $type = factory(Type::class)->create();
        $response = $this->postJson($this->api, ['name' => 'Test of name', 'type_id' => $type->id]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => ['type', 'id', 'attributes' => ['name', 'slug'], 'relationships' => ['type']], 'included', 'error', 'message']);
        $this->assertEquals("test-of-name", SubType::first()->slug);
        $this->assertEquals("TEST OF NAME", SubType::first()->name);
    }

    /**
     * @test
     */
    public function update_with_success()
    {
        $type = factory(Type::class)->create();
        $subType = factory(SubType::class)->create();
        $response = $this->patchJson("$this->api/$subType->id", ['name' => 'Test of name2', 'type_id' => $type->id]);
        $response->assertStatus(200);
        $this->assertEquals("test-of-name2", SubType::first()->slug);
    }

    /**
     * @test
     */
    public function destroy_with_success()
    {
        $subType = factory(SubType::class)->create();
        $this->assertNotNull(SubType::first());
        $response = $this->deleteJson("$this->api/$subType->id");
        $response->assertStatus(200);
        $this->assertNull(SubType::first());
    }
    /**
     * @test
     */
    public function dont_destroy_type_with_one_or_more_property()
    {
        $subType = factory(SubType::class)->create();
        $this->assertNotNull(SubType::first());
        $subType->properties()->save(factory(Property::class)->make());
        $response = $this->deleteJson("$this->api/$subType->id");
        $response->assertStatus(400);
        $this->assertNotNull(SubType::first());
        $this->assertEquals(Terminologies::get('all.sub_type.not_delete_with_relations'), $response->json()['message']);
    }

    /**
     * @test
     */
    public function validate_data_request()
    {
        $type = factory(Type::class)->create();
        $subType = factory(SubType::class)->create(['name' => 'teste', 'type_id' => $type->id]);

        $response = $this->postJson($this->api, ['name' => Str::random(30), 'type_id' => $type->id]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson($this->api, ['name' => Str::random(31), 'type_id' => $type->id]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson($this->api, ['name' => '', 'type_id' => $type->id]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson($this->api, ['name' => 'teste', 'type_id' => $type->id]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson("$this->api/$subType->id", []);
        $response->assertStatus(Response::HTTP_OK);

        $response = $this->patchJson("$this->api/$subType->id", ['name' => 'teste', 'type_id' => $type->id]);
        $response->assertStatus(Response::HTTP_OK);
    }
}
