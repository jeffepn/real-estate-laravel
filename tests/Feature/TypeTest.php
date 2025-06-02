<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class TypeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group type
     * @group type-show
     */
    public function verify_format_return_index()
    {
        Type::factory()->create();
        $response = $this->getJson(route('jp_realestate.api.type.index'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [['type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['sub_types']]],
        ], $response->json());
    }

    /**
     * @test
     * @group type
     */
    public function verify_format_return_show()
    {
        $type = Type::factory()->create();
        $response = $this->getJson(route('jp_realestate.api.type.show', $type->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['sub_types'],
            ],
        ], $response->json());
        $this->assertEquals([
            'type' => 'type',
            'id' => $type->id,
            'attributes' => [
                'slug' => $type->slug,
                'name' => Str::title($type->name),
            ], 'relationships' => ['sub_types' => ['data' => []]],
        ], $response->json()['data']);
    }

    /**
     * @test
     * @group type
     */
    public function store_with_success()
    {
        $response = $this->postJson(route('jp_realestate.api.type.store'), ['name' => 'Test of name']);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['data' => [
            'type', 'id', 'attributes' => ['slug', 'name'], 'relationships' => ['sub_types' => ['data' => []]],
        ], 'error', 'message']);
        $this->assertEquals('test-of-name', Type::first()->slug);
        $this->assertEquals('TEST OF NAME', Type::first()->name);
    }

    /**
     * @test
     * @group type
     */
    public function update_with_success()
    {
        $type = Type::factory()->create();
        $response = $this->patchJson(
            route('jp_realestate.api.type.update', $type->id),
            ['name' => 'Test of name2']
        );
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('test-of-name2', Type::first()->slug);
    }

    /**
     * @test
     * @group type
     */
    public function destroy_with_success()
    {
        $type = Type::factory()->create();
        $this->assertNotNull(Type::first());
        $response = $this->deleteJson(route('jp_realestate.api.type.destroy', $type->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertNull(Type::first());
    }

    /**
     * @test
     * @group type
     */
    public function dont_destroy_type_with_one_or_more_sub_type()
    {
        $type = Type::factory()->create();
        $this->assertNotNull(Type::first());
        $type->sub_types()->save(SubType::factory()->make());
        $response = $this->deleteJson(route('jp_realestate.api.type.destroy', $type->id));
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertNotNull(Type::first());
        $this->assertEquals(Terminologies::get('all.type.not_delete_with_relations'), $response->json()['message']);
    }

    /**
     * @test
     * @group type
     */
    public function validate_name_request()
    {
        // $request = new typeRequest();
        $type = Type::factory()->create(['name' => 'teste']);

        $response = $this->postJson(route('jp_realestate.api.type.store'), ['name' => Str::random(30)]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson(route('jp_realestate.api.type.store'), ['name' => Str::random(31)]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('jp_realestate.api.type.store'), ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('jp_realestate.api.type.store'), ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson(route('jp_realestate.api.type.update', $type->id), ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_OK);
    }
}
