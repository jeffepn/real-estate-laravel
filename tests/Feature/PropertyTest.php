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
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use JPAddress\Models\Address\Address;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    protected $api = '/api/property';


    /**
     * @test
     */
    public function verify_format_return_index()
    {
        $subType = factory(Type::class)->create()->sub_types()->save(factory(SubType::class)->make());
        factory(Property::class, 20)->create([
            'business_id' => factory(Business::class)->create()->id,
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
                        'slug', 'building_area', 'total_area', 'min_description',
                        'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                        'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage'
                    ],
                    'relationships' => ['sub_type', 'address', 'business'],
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
                    'slug', 'building_area', 'total_area', 'min_description',
                    'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                    'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage'
                ],
                'relationships' => ['sub_type', 'address', 'business'],
            ],
            'included'
        ], $response->json());
        $this->assertEquals([
            'type' => 'property',
            'id' => $property->id,
            'attributes' => [
                'slug' => $property->slug,
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
                'business' => ['data' => ['type' => 'business', 'id' => $property->business_id]],
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
        $address = $this->createAddress();
        $response = $this->postJson(
            $this->api,
            [
                'max_dormitory' => 3,
                'business_id' => $business->id, 'address_id' => $address->id, 'sub_type_id' => $subType->id,
                'min_description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit.
                 Asperiores exercitationem placeat "
            ]
        );
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'type', 'id',
                'attributes' => [
                    'slug', 'building_area', 'total_area', 'min_description',
                    'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                    'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage'
                ],
                'relationships' => [
                    'address', 'business', 'sub_type'
                ]
            ],
            'included', 'error', 'message'
        ]);
    }
}
