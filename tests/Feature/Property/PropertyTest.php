<?php

namespace Jeffpereira\RealEstate\Tests\Feature\Property;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Events\BusinessPropertyFinalizedEvent;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @group property
     * @group property-index
     * @group index
     */
    public function verifyFormatReturnIndex()
    {
        $subType = Type:: factory()->create()->sub_types()->save(SubType::factory()->make());
        Property::factory(20)->create([
            'sub_type_id' => $subType->id,
            'address_id' => $this->createAddress()->id,
            'max_dormitory' => function () {
                return rand(1, 5);
            },
        ]);
        $response = $this->getJson(route('jp_realestate.api.property.index'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                [
                    'type', 'id',
                    'attributes' => [
                        'slug', 'code', 'building_area', 'total_area', 'min_description',
                        'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                        'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage',
                    ],
                    'relationships' => ['situation', 'sub_type', 'address', 'businesses'],
                ],
            ],
            'included',
        ], $response->json());
    }

    /**
     * @test
     * @group property
     * @group property-show
     * @group show
     */
    public function verifyFormatReturnShow()
    {
        $property = Property::factory()->create();
        $response = $this->getJson(route('jp_realestate.api.property.show', $property->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'type', 'id',
                'attributes' => [
                    'slug', 'code', 'building_area', 'total_area', 'min_description',
                    'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                    'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage',
                    'useful_area', 'embed', 'active',
                ],
                'relationships' => ['sub_type', 'address'],
            ],
            'included',
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
                'ground_area' => $property->ground_area,
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
                'min_restroom' => $property->min_restroom,
                'max_restroom' => $property->max_restroom,
                'useful_area' => $property->useful_area,
                'embed' => $property->embed,
                'has_plate' => $property->has_plate,
                'active' => $property->active,
            ],
            'relationships' => [
                'sub_type' => ['data' => ['type' => 'sub_type', 'id' => $property->sub_type_id]],
                'address' => ['data' => ['type' => 'address', 'id' => $property->address_id]],
                'situation' => ['data' => ['type' => 'situation', 'id' => $property->situation_id]],
                'businesses' => $property->businesses->map(function ($business) {
                    return ['data' => ['type' => 'business', 'id' => $business->id]];
                })->toArray(),
            ],
        ], $response->json()['data']);
    }

    /**
     * @test
     * @group property
     * @group property-store
     * @group store
     */
    public function storeWithSuccess()
    {
        $situation = $this->faker->boolean() ? Situation::factory()->create() : null;
        $subType = SubType::factory()->create();
        $businessSale = Business::factory()->sale()->create();
        $businessRent = Business::factory()->rent()->create();
        $response = $this->postJson(
            route('jp_realestate.api.property.store'),
            [
                'sub_type_id' => $subType->id,
                'situation_id' => $situation ? $situation->id : null,
                'min_description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores exercitationem placeat',
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
                'min_restroom' => 3,
                'max_restroom' => 7,
                'address' => 'av. campos do jordão',
                'number' => 2324,
                'complement' => 'apt. 6',
                'cep' => '99999-999',
                'latitude' => 45,
                'longitude' => 123,
                'neighborhood' => 'Jd Santa Maria',
                'city' => 'Poços de Caldas',
                'state' => 'Minas Gerais',
                'initials' => 'MG',
                'country' => 'Brasil',
                'useful_area' => 200,
                'ground_area' => 100,
                'embed' => 'http://google.com',
                'businesses' => [
                    ['id' => $businessSale->id, 'value' => 100000, 'old_value' => 200000],
                    ['id' => $businessRent->id, 'value' => 1000, 'old_value' => 200],
                ],
            ]
        );
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'type', 'id',
                'attributes' => [
                    'slug', 'code', 'building_area', 'total_area', 'min_description',
                    'content', 'items', 'min_dormitory', 'max_dormitory', 'min_bathroom',
                    'max_bathroom', 'min_suite', 'max_suite', 'min_garage', 'max_garage', 'ground_area', 'useful_area',
                ],
                'relationships' => [
                    'address', 'sub_type', 'situation', 'businesses',
                ],
            ],
            'included', 'error', 'message',
        ]);
        $property = Property::first();

        $property->refresh();
        $this->assertEquals([
            'type' => 'property', 'id' => $property->id, 'attributes' => [
                'slug' => $property->slug, 'code' => $property->code, 'building_area' => 105.49, 'total_area' => 200.50,
                'min_description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores exercitationem placeat',
                'min_dormitory' => 1, 'max_dormitory' => 3, 'min_suite' => 2, 'max_suite' => 4, 'min_bathroom' => 3, 'max_bathroom' => 6,
                'min_garage' => 4, 'max_garage' => 8, 'min_restroom' => 3, 'max_restroom' => 7, 'content' => 'test content', 'items' => 'test items',
                'useful_area' => 200, 'ground_area' => 100, 'embed' => 'http://google.com', 'has_plate' => false,
                'active' => false,
            ],
            'relationships' => [
                'address' => ['data' => ['type' => 'address', 'id' => $property->address_id]],
                'sub_type' => ['data' => ['type' => 'sub_type', 'id' => $property->sub_type_id]],
                'situation' => ['data' => ['type' => 'situation', 'id' => $property->situation_id]],
                'businesses' => $property->businessesProperty->map(function ($businessProperty) {
                    return ['data' => ['type' => 'business_property', 'id' => $businessProperty->id]];
                })->toArray(),
            ],
        ], $response->json()['data']);
        $this->assertEquals(2, $property->businesses->count());
        $this->assertEquals(
            $businessRent->id,
            $property->businesses->filter(function ($b) {
                return $b->name === 'ALUGUEL';
            })->first()->id
        );
        $this->assertEquals(
            $businessSale->id,
            $property->businesses->filter(function ($b) {
                return $b->name === 'VENDA';
            })->first()->id
        );
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
     * @group property
     * @group property-update
     * @group update
     */
    public function updateWithSuccess()
    {
        $situation = Situation::factory()->create(); //$this->faker->boolean() ? Situation::factory()->create() : null;
        $subType = SubType::factory()->create();
        $address = $this->createAddress();
        $property = Property::create([
            'situation_id' => $situation ? $situation->id : null,
            'sub_type_id' => $subType->id,
            'address_id' => $address->id,
            'min_description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores exercitationem placeat',
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
        $response = $this->patchJson(route('jp_realestate.api.property.update', $property->id), [
            'min_description' => 'min description edit',
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
            'min_restroom' => 2,
            'max_restroom' => 4,
            'number' => 789,
            'neighborhood' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'initials' => $this->faker->stateAbbr(),
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $data = $property->refresh()->toArray();
        unset($data['created_at'], $data['updated_at'], $data['business'], $data['sub_type'], $data['address']);

        $this->assertEquals([
            'id' => $property->id,
            'slug' => $property->slug,
            'code' => $property->code,
            'address_id' => $property->address_id,
            'sub_type_id' => $property->sub_type_id,
            'situation_id' => $property->situation_id,
            'min_description' => 'min description edit',
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
            'min_restroom' => 2,
            'max_restroom' => 4,
            'useful_area' => $property->useful_area,
            'ground_area' => $property->ground_area,
            'embed' => $property->embed,
            'has_plate' => $property->has_plate,
            'active' => $property->active,
        ], $data);
        $this->assertEquals(789, $property->address->number);
    }

    /**
     * @test
     * @group property
     * @group property-update
     * @group update
     */
    public function updatePropertyWithAddingBusinesses()
    {
        $property = Property::factory()->create();
        $businessSale = Business::factory()->sale()->create();
        $businessRent = Business::factory()->rent()->create();
        $response = $this->patchJson(route('jp_realestate.api.property.update', $property->id), [
            'neighborhood' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'initials' => $this->faker->stateAbbr(),
            'businesses' => [
                ['id' => $businessSale->id, 'value' => 100000],
                ['id' => $businessRent->id, 'value' => 1000],
            ],
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $property->refresh();
        $this->assertEquals(
            $businessRent->id,
            $property->businesses->filter(function ($b) {
                return $b->name === 'ALUGUEL';
            })->first()->id
        );
        $this->assertEquals(
            $businessSale->id,
            $property->businesses->filter(function ($b) {
                return $b->name === 'VENDA';
            })->first()->id
        );
    }

    /**
     * @test
     * @group property
     * @group property-update
     * @group update
     */
    public function updatePropertyWithAddingBusinessesSituation()
    {
        Event::fake(BusinessPropertyFinalizedEvent::class);
        $property = Property::factory()->create();
        $businessSale = Business::factory()->sale()->create();
        $businessRent = Business::factory()->rent()->create();
        $property->businessesProperty()
            ->save(
                BusinessProperty::factory()
                    ->make(['business_id' => $businessRent->id])
            );
        $response = $this->patchJson(route('jp_realestate.api.property.update', $property->id), [
            'neighborhood' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'initials' => $this->faker->stateAbbr(),
            'businesses' => [
                ['id' => $businessSale->id, 'value' => 100000],
                ['id' => $businessRent->id, 'value' => 1000, 'status_situation' => BusinessPropertySituationEnum::COMPLETED],
            ],
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $property->refresh();
        $businessPropertyRent = $property->businessesProperty->filter(function ($b) {
            return $b->business->name === 'ALUGUEL';
        })->first();
        $this->assertEquals($businessRent->id, $businessPropertyRent->business_id);
        $businessPropertySale = $property->businessesProperty->filter(function ($b) {
            return $b->business->name === 'VENDA';
        })->first();
        $this->assertEquals($businessSale->id, $businessPropertySale->business_id);
        $this->assertFalse($businessPropertySale->isCompleted);
        Event::assertDispatched(BusinessPropertyFinalizedEvent::class);
    }

    /**
     * @test
     * @group property
     * @group property-update
     * @group update
     */
    public function updatePropertyWithRemoveBusiness()
    {
        $property = Property::factory()->create();
        $businessSale = Business::factory()->sale()->create();
        $businessRent = Business::factory()->rent()->create();
        foreach ([$businessSale, $businessRent] as $currentBusiness) {
            BusinessProperty::factory()
                ->create(['property_id' => $property->id, 'business_id' => $currentBusiness->id]);
        }

        $this->assertEquals(2, $property->businesses()->count());

        $response = $this->patchJson(route('jp_realestate.api.property.update', $property->id), [
            'neighborhood' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'initials' => $this->faker->stateAbbr(),
            'businesses' => [
                ['id' => $businessSale->id, 'value' => 100000],
            ],
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(1, $property->businesses()->count());
        $this->assertEquals($businessSale->id, $property->businesses()->first()->id);
    }

    /**
     * @test
     * @group property
     * @group property-destroy
     * @group destroy
     */
    public function destroyWithSuccess()
    {
        $property = Property::factory()->create();
        $this->assertNotNull(Property::first());
        $response = $this->deleteJson(route('jp_realestate.api.property.destroy', $property->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertNull(Property::first());
    }

    /**
     * @test
     * @group property
     * @group property-validation
     * @group validation
     */
    public function validateDataRequest()
    {
        $business = Business::factory()->create();
        $type = Type:: factory()->create();
        $subType = SubType::factory()->create(['name' => 'teste', 'type_id' => $type->id]);
        $data = [
            'slug' => 'test-slug',
            'businesses' => [
                [
                    'id' => $business->id,
                    'value' => 1000.00,
                    'status_situation' => BusinessPropertySituationEnum::COMPLETED,
                ],
            ],
            'sub_type_id' => $subType->id,
            'min_description' => Str::random(Response::HTTP_OK),
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
            'neighborhood' => 'Jd Santa Maria',
            'city' => 'Poços de Caldas',
            'state' => 'Minas Gerais',
            'initials' => 'MG',
            'country' => 'Brasil',
        ];

        $response = $this->postJson(route('jp_realestate.api.property.store'), $data);
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
            ['key' => 'number', 'value' => -20],
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
            ['key' => 'businesses', 'value' => [['id' => 9999, 'value' => rand(10, 10000)]]],
            ['key' => 'businesses', 'value' => [['id' => $business->id, 'value' => 'oiiooi']]],
            ['key' => 'businesses', 'value' => [['id' => $business->id, 'value' => rand(10, 10000), 'status_situation' => 9]]],
        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            $aux[$item['key']] = $item['value'];
            $response = $this->postJson(route('jp_realestate.api.property.store'), $aux);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $property = Property::first();
        // Test update slug, when is ignored
        $response = $this->patchJson(
            route('jp_realestate.api.property.update', $property->id),
            [
                'slug' => 'test-slug',
                'neighborhood' => 'Jd Santa Maria',
                'city' => 'Poços de Caldas',
                'state' => 'Minas Gerais',
                'initials' => 'MG',
                'country' => 'Brasil',
            ]
        );
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @group feature
     * @group property
     * @group scope
     * @group scope-active
     */
    public function routeInactiveProperty()
    {
        $property = Property::factory()->active()->create();
        $this->assertTrue($property->isActive);

        $response = $this->patchJson(
            route('jp_realestate.api.property.active_or_inactive', $property->id),
            ['active' => false]
        );
        $property->refresh();

        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue($property->isInactive);
    }

    /**
     * @test
     * @group feature
     * @group property
     * @group scope
     * @group scope-active
     */
    public function routeActivePropertyWithErrorWhenNotPossibleAcivateWithoutBusinesses()
    {
        $property = Property::factory()->inactive()->create();
        $property->images()
            ->createMany(
                ImageProperty::factory(2)
                    ->make()
                    ->makeHidden(['way_url'])
                    ->toArray()
            );
        $this->assertTrue($property->isInactive);
        $this->assertEquals(2, $property->images()->count());

        $response = $this->patchJson(
            route('jp_realestate.api.property.active_or_inactive', $property->id),
            ['active' => true]
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @test
     * @group feature
     * @group property
     * @group scope
     * @group scope-active
     */
    public function routeActivePropertyWithErrorWhenNotPossibleAcivateWithoutImages()
    {
        $property = Property::factory()->inactive()->create();
        $property->businessesProperty()
            ->createMany(
                BusinessProperty::factory(2)
                    ->make()
                    ->toArray()
            );
        $this->assertTrue($property->isInactive);
        $this->assertEquals(2, $property->businesses()->count());

        $response = $this->patchJson(
            route('jp_realestate.api.property.active_or_inactive', $property->id),
            ['active' => true]
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @test
     * @group feature
     * @group property
     * @group scope
     * @group scope-active
     */
    public function routeActivePropertyWithSuccessWhenHasImagesAndBusiness()
    {
        $property = Property::factory()->inactive()->create();
        $property->businessesProperty()
            ->createMany(
                BusinessProperty::factory(3)
                    ->make()
                    ->toArray()
            );
        $property->images()
            ->createMany(
                ImageProperty::factory(2)
                    ->make()
                    ->makeHidden(['way_url'])
                    ->toArray()
            );
        $this->assertTrue($property->isInactive);
        $this->assertEquals(3, $property->businesses()->count());
        $this->assertEquals(2, $property->images()->count());

        $response = $this->patchJson(
            route('jp_realestate.api.property.active_or_inactive', $property->id),
            ['active' => true]
        );
        $property->refresh();

        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue($property->isActive);
    }

    // Scopes

    /**
     * @test
     * @group feature
     * @group property
     * @group scope
     * @group scope-active
     */
    public function propertyIsActiveAndNotActive()
    {
        $property = Property::factory()->active()->create();
        $this->assertCount(1, Property::active()->get());
        $this->assertCount(0, Property::notActive()->get());
        $property->active = false;
        $property->save();
        $this->assertCount(0, Property::active()->get());
        $this->assertCount(1, Property::notActive()->get());
    }
}
