<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Http\Requests\Property\BusinessRequest;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use JPAddress\Models\Address\Address;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    protected $api = 'property';


    /**
     * @test
     */
    public function verify_format_return_index()
    {
        $subType = factory(Type::class)->create()->sub_types()->save(factory(SubType::class)->make());
        factory(Property::class, 20)->create([
            'sub_type_id' => $subType->id,
            'address_id' => $this->createAddress()->id,
            'max_dormitory' => function () {
                return rand(1, 5);
            }
        ]);
        $response = $this->getJson($this->api);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'type', 'id',
                    'attributes' => [
                        'slug', "code", 'building_area', 'total_area', 'min_description',
                        'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                        'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage'
                    ],
                    'relationships' => ['sub_type', 'address'],
                ]
            ],
            'included'
        ], $response->json());
    }

    /**
     * @test
     */
    public function verify_format_return_show()
    {
        $property = factory(Property::class)->create();
        $response = $this->getJson("$this->api/$property->id");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                'type', 'id',
                'attributes' => [
                    'slug', 'code', 'building_area', 'total_area', 'min_description',
                    'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                    'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage'
                ],
                'relationships' => ['sub_type', 'address'],
            ],
            'included'
        ], $response->json());
        $property->refresh();
        $this->assertEquals([
            'type' => 'property',
            'id' => $property->id,
            'attributes' => [
                'slug' => $property->slug,
                'code' => $property->code,
                'building_area' => $property->building_area,
                'total_area' => $property->total_area,
                'min_description' => $property->min_description,
                'content' => $property->content,
                'items' => $property->items,
                'min_dormitory' => $property->min_dormitory,
                'max_dormitory' => $property->max_dormitory,
                'min_bathroom' => $property->min_bathroom,
                'max_bathroom' => $property->max_bathroom,
                'min_suite' => $property->min_suite,
                'max_suite' => $property->max_suite,
                'min_garage' => $property->min_garage,
                'max_garage' => $property->max_garage,
            ],
            'relationships' => [
                'sub_type' => ['data' => ['type' => 'sub_type', 'id' => $property->sub_type_id]],
                'address' => ['data' => ['type' => 'address', 'id' => $property->address_id]],
            ]
        ], $response->json()['data']);
    }

    /**
     * @test
     */
    public function store_with_success()
    {
        $business = factory(Business::class)->create();
        $subType = factory(SubType::class)->create();
        $response = $this->postJson(
            $this->api,
            [
                'sub_type_id' => $subType->id,
                'min_description' =>
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores exercitationem placeat",
                'content' => 'test content', 'items' => 'test items',
                'building_area' => 105.49, 'total_area' => 200.50,
                'min_dormitory' => 1,
                'max_dormitory' => 3,
                'min_suite' => 2,
                'max_suite' => 4,
                'min_bathroom' => 3,
                'max_bathroom' => 6,
                'min_garage' => 4,
                'max_garage' => 8,
                'address' => 'av. campos do jordão',
                'number' => 2324,
                'complement' => 'apt. 6',
                'cep' => '99999-999',
                'latitude' => 45,
                'longitude' => 123,
                "neighborhood" => "Jd Santa Maria",
                "city" => "Poços de Caldas",
                "state" => "Minas Gerais",
                "initials" => "MG",
                "country" => "Brasil"
            ]
        );
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'type', 'id',
                'attributes' => [
                    'slug', 'code', 'building_area', 'total_area', 'min_description',
                    'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                    'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage'
                ],
                'relationships' => [
                    'address', 'sub_type'
                ]
            ],
            'included', 'error', 'message'
        ]);
        $property = Property::first();

        $property->refresh();
        $this->assertEquals([
            'type' => 'property', 'id' => $property->id, 'attributes' => [
                'slug' => $property->slug, 'code' => $property->code, 'building_area' => 105.49, 'total_area' => 200.50,
                'min_description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores exercitationem placeat",
                'min_dormitory' => 1, 'max_dormitory' => 3, 'min_suite' => 2, 'max_suite' => 4, 'min_bathroom' => 3, 'max_bathroom' => 6,
                'min_garage' => 4, 'max_garage' => 8, 'content' => 'test content', 'items' => 'test items'
            ],
            'relationships' => [
                'address' => ['data' => ['type' => 'address', 'id' => $property->address_id]],
                'sub_type' => ['data' => ['type' => 'sub_type', 'id' => $property->sub_type_id]],
            ]
        ], $response->json()['data']);
        $this->assertEquals('av. campos do jordão', $property->address->address);
        $this->assertEquals(2324, $property->address->number);
        $this->assertEquals('apt. 6', $property->address->complement);
        $this->assertEquals('99999999', $property->address->cep);
        $this->assertEquals(45, $property->address->latitude);
        $this->assertEquals(123, $property->address->longitude);
        $this->assertEquals(Str::upper('Jd Santa Maria'), $property->address->neighborhood->name);
        $this->assertEquals(Str::upper('Poços de Caldas'), $property->address->neighborhood->city->name);
        $this->assertEquals(Str::upper('Minas Gerais'), $property->address->neighborhood->city->state->name);
        $this->assertEquals('MG', $property->address->neighborhood->city->state->initials);
        $this->assertEquals(Str::upper('Brasil'), $property->address->neighborhood->city->state->country->name);
    }

    /**
     * @test
     */
    public function update_with_success()
    {
        $business = factory(Business::class)->create();
        $subType = factory(SubType::class)->create();
        $address = $this->createAddress();
        $property = Property::create([
            'sub_type_id' => $subType->id,
            'address_id' => $address->id,
            'min_description' =>
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores exercitationem placeat",
            'content' => 'test content', 'items' => 'test items',
            'building_area' => 105.49, 'total_area' => 200.50,
            'min_dormitory' => 1,
            'max_dormitory' => 3,
            'min_suite' => 2,
            'max_suite' => 4,
            'min_bathroom' => 3,
            'max_bathroom' => 6,
            'min_garage' => 4,
            'max_garage' => 8,
        ]);
        $response = $this->patchJson("$this->api/$property->id", [
            'min_description' => "min description edit",
            'content' => 'test content edit', 'items' => 'test items edit',
            'building_area' => 115.49, 'total_area' => 210.50,
            'min_dormitory' => 2,
            'max_dormitory' => 6,
            'min_suite' => 3,
            'max_suite' => 5,
            'min_bathroom' => 2,
            'max_bathroom' => 7,
            'min_garage' => 5,
            'max_garage' => 9,
        ]);
        $response->assertStatus(200);
        $data = $property->refresh()->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['business']);
        unset($data['sub_type']);
        unset($data['address']);
        $this->assertEquals([
            'id' => $property->id,
            'slug' => $property->slug,
            'code' => $property->code,
            'address_id' => $property->address_id,
            'sub_type_id' => $property->sub_type_id,
            'min_description' => "min description edit",
            'content' => 'test content edit', 'items' => 'test items edit',
            'building_area' => 115.49, 'total_area' => 210.50,
            'min_dormitory' => 2,
            'max_dormitory' => 6,
            'min_suite' => 3,
            'max_suite' => 5,
            'min_bathroom' => 2,
            'max_bathroom' => 7,
            'min_garage' => 5,
            'max_garage' => 9,
            "active" => 0
        ], $data);
    }

    /**
     * @test
     */
    public function destroy_with_success()
    {
        $property = factory(Property::class)->create();
        $this->assertNotNull(Property::first());
        $response = $this->deleteJson("$this->api/$property->id");
        $response->assertStatus(200);
        $this->assertNull(Property::first());
    }

    /**
     * @test
     */
    public function validate_data_request()
    {
        $business = factory(Business::class)->create();
        $type = factory(Type::class)->create();
        $subType = factory(SubType::class)->create(['name' => 'teste', 'type_id' => $type->id]);
        $data = [
            'slug' => 'test-slug',
            'businesses' => [
                [
                    "id" => $business->id,
                    "value" => 1000.00
                ]
            ],
            'sub_type_id' => $subType->id,
            'min_description' =>  Str::random(200),
            'content' => 'test content', 'items' => 'test items',
            'building_area' => 105.49, 'total_area' => 200.50,
            'min_dormitory' => 1,
            'max_dormitory' => 3,
            'min_suite' => 2,
            'max_suite' => 4,
            'min_bathroom' => 3,
            'max_bathroom' => 6,
            'min_garage' => 4,
            'max_garage' => 8,
            "neighborhood" => "Jd Santa Maria",
            "city" => "Poços de Caldas",
            "state" => "Minas Gerais",
            "initials" => "MG",
            "country" => "Brasil"
        ];
        $response = $this->postJson($this->api, $data);
        // dd($response->json());
        $response->assertStatus(Response::HTTP_CREATED);
        $data['slug'] = 'test-slug-2';
        $array_validation = [
            ['key' => 'sub_type_id', 'value' => null],
            ['key' => 'sub_type_id', 'value' => 'kkkk'],
            ['key' => 'min_description', 'value' => Str::random(9)],
            ['key' => 'min_description', 'value' => Str::random(201)],
            ['key' => 'slug', 'value' => Str::lower(Str::random(2))],
            ['key' => 'slug', 'value' => Str::lower(Str::random(151))],
            ['key' => 'slug', 'value' => 'test of slug with format incorrect'],
            ['key' => 'slug', 'value' => 'test-slug'],
            ['key' => 'total_area', 'value' => 'jjj'],
            ['key' => 'building_area', 'value' => 'kk'],
            ['key' => 'min_dormitory', 'value' => 'kk'],
            ['key' => 'min_dormitory', 'value' => -1],
            ['key' => 'min_dormitory', 'value' => 4],
            ['key' => 'max_dormitory', 'value' => 'kk'],
            ['key' => 'max_dormitory', 'value' => -1],
            ['key' => 'min_suite', 'value' => 'kk'],
            ['key' => 'min_suite', 'value' => -1],
            ['key' => 'min_suite', 'value' => 5],
            ['key' => 'max_suite', 'value' => 'kk'],
            ['key' => 'max_suite', 'value' => -1],
            ['key' => 'min_bathroom', 'value' => 'kk'],
            ['key' => 'min_bathroom', 'value' => -1],
            ['key' => 'min_bathroom', 'value' => 7],
            ['key' => 'max_bathroom', 'value' => 'kk'],
            ['key' => 'max_bathroom', 'value' => -1],
            ['key' => 'min_garage', 'value' => 'kk'],
            ['key' => 'min_garage', 'value' => -1],
            ['key' => 'min_garage', 'value' => 9],
            ['key' => 'max_garage', 'value' => 'kk'],
            ['key' => 'max_garage', 'value' => -1],
            ['key' => 'address', 'value' => Str::random(101)],
            ['key' => 'number', 'value' => 'jjk'],
            ['key' => 'number', 'value' => 0],
            ['key' => 'complement', 'value' => Str::random(16)],
            ['key' => 'cep', 'value' => Str::random(16)],
            ['key' => 'latitude', 'value' => 'fsfd'],
            ['key' => 'longitude', 'value' => 'fsfd'],
            ['key' => 'neighborhood', 'value' => null],
            ['key' => 'neighborhood', 'value' => ''],
            ['key' => 'neighborhood', 'value' => 'f'],
            ['key' => 'neighborhood', 'value' => Str::random(101)],
            ['key' => 'city', 'value' => null],
            ['key' => 'city', 'value' => ''],
            ['key' => 'city', 'value' => 'f'],
            ['key' => 'city', 'value' => Str::random(101)],
            ['key' => 'state', 'value' => 'f'],
            ['key' => 'state', 'value' => Str::random(101)],
            ['key' => 'initials', 'value' => null],
            ['key' => 'initials', 'value' => ''],
            ['key' => 'initials', 'value' => 'f'],
            ['key' => 'initials', 'value' => Str::random(3)],

        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            $aux[$item['key']] = $item['value'];
            $response = $this->postJson($this->api, $aux);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $property = Property::first();
        // Test update slug, when is ignored
        $response = $this->patchJson("$this->api/$property->id", ['slug' => 'test-slug']);
        $response->assertStatus(Response::HTTP_OK);
    }
}
