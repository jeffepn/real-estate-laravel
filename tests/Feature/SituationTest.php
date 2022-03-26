<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class SituationTest extends TestCase
{
    use RefreshDatabase;
    const URL_API = 'api/situation';

    /**
     * @test
     * @group situation
     * @group situation-index
     */
    public function verifyFormatReturnIndex()
    {
        factory(Situation::class)->create();
        $response = $this->getJson(self::URL_API);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'type', 'id',
                    'attributes' => ['slug', 'name']
                ]
            ]
        ], $response->json());
    }

    /**
     * @test
     * @group situation
     * @group situation-show
     */
    public function verifyFormatReturnShow()
    {
        $situation = factory(Situation::class)->create();
        $response = $this->getJson(self::URL_API . "/$situation->id");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                'type', 'id', 'attributes' => ['slug', 'name']
            ]
        ], $response->json());
        $this->assertEquals([
            'type' => 'situation',
            'id' => $situation->id,
            'attributes' => [
                'slug' => $situation->slug,
                'name' => Str::title($situation->name),
            ]
        ], $response->json()['data']);
    }
    /**
     * @test
     * @group situation
     * @group situation-store
     */
    public function storeWitSuccess()
    {
        $response = $this->postJson(self::URL_API, ['name' => 'Test of name']);
        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => [
            'type', 'id', 'attributes' => ['slug', 'name']
        ], 'error', 'message']);

        $this->assertEquals(2, Situation::count());
        $this->assertEquals("test-of-name", Situation::firstWhere('name', 'TEST OF NAME')->slug);
        $this->assertEquals("TEST OF NAME", Situation::firstWhere('name', 'TEST OF NAME')->name);
    }

    /**
     * @test
     * @group situation
     * @group situation-update
     */
    public function updateWithSuccess()
    {
        $situation = factory(Situation::class)->create();
        $response = $this->patchJson(self::URL_API . "/$situation->id", ['name' => 'Test of name2']);
        $response->assertStatus(200);
        $this->assertEquals("test-of-name2", Situation::firstWhere('name', 'TEST OF NAME2')->slug);
    }

    /**
     * @test
     * @group situation
     * @group situation-destroy
     */
    public function destroyWithSuccess()
    {
        $situation = factory(Situation::class)->create();
        $this->assertNotNull(Situation::first());
        $response = $this->deleteJson(self::URL_API . "/$situation->id");
        $response->assertStatus(200);
        $this->assertNull(Situation::find($situation->id));
    }

    /**
     * @test
     * @group situation
     * @group situation-destroy
     * @group situation-destroy-error
     */
    public function dontDestroyTypeWithOneOrMoreProperty()
    {
        $situation = factory(Situation::class)->create();
        $this->assertNotNull(Situation::first());
        $situation->properties()->save(factory(Property::class)->make());
        $response = $this->deleteJson(self::URL_API . "/$situation->id");
        $response->assertStatus(400);
        $this->assertNotNull(Situation::first());
        $this->assertEquals(Terminologies::get('all.type.not_delete_with_relations'), $response->json()['message']);
    }

    /**
     * @test
     * @group situation
     * @group situation-validation
     */
    public function validateNameRequest()
    {
        // $request = new typeRequest();
        $situation = factory(Situation::class)->create(['name' => 'teste']);

        $response = $this->postJson(self::URL_API, ['name' => Str::random(30)]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson(self::URL_API, ['name' => Str::random(31)]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(self::URL_API, ['name' => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(self::URL_API, ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->patchJson(self::URL_API . "/$situation->id", ['name' => 'teste']);
        $response->assertStatus(Response::HTTP_OK);
    }
}