<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class TypeTest extends TestCase
{
    use RefreshDatabase;
    protected $api = '/api/type';

    /**
     * @test
     */
    public function verify_format_return_index()
    {
        factory(Type::class)->create();
        $response = $this->getJson($this->api);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['sub_types']]]
        ], $response->json());
    }

    /**
     * @test
     */
    public function verify_format_return_show()
    {
        $type = factory(Type::class)->create();
        $response = $this->getJson("$this->api/$type->id");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                'type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['sub_types']
            ]
        ], $response->json());
        $this->assertEquals([
            'type' => 'type',
            'id' => $type->id,
            'attributes' => [
                'slug' => $type->slug,
                'name' => Str::title($type->name),
            ], 'relationships' => ['sub_types' => ['data' => []]]
        ], $response->json()['data']);
    }
    /**
     * @test
     */
    public function store_with_success()
    {
        $response = $this->postJson($this->api, ['name' => 'Test of name']);
        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => [
            'type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['sub_types' => ['data' => []]]
        ], 'error', 'message']);
        $this->assertEquals("test-of-name", Type::first()->slug);
        $this->assertEquals("TEST OF NAME", Type::first()->name);
    }

    /**
     * @test
     */
    public function update_with_success()
    {
        $type = factory(Type::class)->create();
        $response = $this->patchJson("$this->api/$type->id", ['name' => 'Test of name2']);
        $response->assertStatus(200);
        $this->assertEquals("test-of-name2", Type::first()->slug);
    }

    /**
     * @test
     */
    public function destroy_with_success()
    {
        $type = factory(Type::class)->create();
        $this->assertNotNull(Type::first());
        $response = $this->deleteJson("$this->api/$type->id");
        $response->assertStatus(200);
        $this->assertNull(Type::first());
    }

    /**
     * @test
     */
    public function dont_destroy_type_with_one_or_more_sub_type()
    {
        $type = factory(Type::class)->create();
        $this->assertNotNull(Type::first());
        $type->sub_types()->save(factory(SubType::class)->make());
        $response = $this->deleteJson("$this->api/$type->id");
        $response->assertStatus(400);
        $this->assertNotNull(Type::first());
        $this->assertEquals(Terminologies::get('all.type.not_delete_with_relations'), $response->json()['message']);
    }

    /**
     * @test
     */
    public function validate_name_request()
    {
        // $request = new typeRequest();
        $type = factory(Type::class)->create(['name' => 'teste']);

        $response = $this->postJson($this->api, ['name' => Str::random(30)]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson($this->api, ['name' => Str::random(31)]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson($this->api, ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson($this->api, ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson("$this->api/$type->id", ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_OK);
    }
}
