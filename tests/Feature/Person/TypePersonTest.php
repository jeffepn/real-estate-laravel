<?php

namespace Jeffpereira\RealEstate\Tests\Feature\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Person\TypePerson;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class TypePersonTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const FORMAT_RESOURCE = [
        'type', 'id',
        'attributes' => [
            'slug', "name"
        ],
        'relationships' => [],
    ];

    const FORMAT_RESOURCE_SHOW = [
        'data' => self::FORMAT_RESOURCE,
        'included' => [],
        'error',
        'message'
    ];

    const FORMAT_RESOURCE_INDEX = [
        'data' => [self::FORMAT_RESOURCE],
        'included' => [],
        'error',
        'message'
    ];

    const DARA_RESOURCE = [
        'data' => [],
        'included' => [],
        'error' => false,
        'message' => ''
    ];

    const DARA_RESOURCE_TYPE = [
        'data' => [
            'type' => 'type_person',
            'id' => null
        ]
    ];

    /**
     * @test
     * @group type_person
     * @group type_person-index
     * @group index
     */
    public function verifyFormatReturnIndex()
    {
        factory(TypePerson::class, 20)->create();
        $response = $this->getJson(route('jp_realestate.api.type_person.index'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_INDEX, $response->json());
    }

    /**
     * @test
     * @group type_person
     * @group type_person-index
     * @group index
     */
    public function verifyDataReturnIndex()
    {
        factory(TypePerson::class, 20)->create();

        $response = $this->getJson(route('jp_realestate.api.type_person.index'));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals([
            'data' => TypePerson::orderBy('name')->get()->map(function ($typePerson) {
                return [
                    'type' => 'type_person',
                    'id' => $typePerson->id,
                    'attributes' => Arr::only($typePerson->toArray(), ['slug', 'name']),
                    'relationships' => [],
                ];
            })->toArray(),
            'included' => [],
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group type_person
     * @group type_person-show
     * @group show
     */
    public function verifyFormatReturnShow()
    {
        $typePerson = factory(TypePerson::class)->create();
        $typePerson->refresh();

        $response = $this->getJson(route('jp_realestate.api.type_person.show', $typePerson->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_SHOW, $response->json());
    }

    /**
     * @test
     * @group type_person
     * @group type_person-show
     * @group show
     */
    public function verifyDataReturnShow()
    {
        $typePerson = factory(TypePerson::class)->create();
        $typePerson->refresh();

        $response = $this->getJson(route('jp_realestate.api.type_person.show', $typePerson->id));

        $response->assertStatus(Response::HTTP_OK);
        $format = self::DARA_RESOURCE;
        $format['data'] = [
            'type' => 'type_person', 'id' => $typePerson->id,
            'attributes' => Arr::only($typePerson->toArray(), ['slug', 'name']),
            'relationships' => [],
        ];
        $this->assertEquals($format, $response->json());
    }

    /**
     * @test
     * @group type_person
     * @group type_person-store
     * @group store
     */
    public function storeWithSuccess()
    {
        $dataStore = [
            'name' =>  $this->faker->name(),
        ];

        $response = $this->postJson(
            route('jp_realestate.api.type_person.store'),
            $dataStore
        );
        $typePerson = TypePerson::first();

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_SHOW);
        $this->assertEquals($dataStore, Arr::only($typePerson->toArray(), ['name']));
    }

    /**
     * @test
     * @group type_person
     * @group type_person-update
     * @group update
     */
    public function updateWithSuccess()
    {
        $typePerson = factory(TypePerson::class)->create();

        $response = $this->patchJson(route('jp_realestate.api.type_person.update', $typePerson->id), [
            'name' => "Other name",
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.save')
            ],
            $response->json()
        );
        $data = TypePerson::find($typePerson->id)->toArray();
        $this->assertEquals([
            'id' => $typePerson->id,
            'slug' => Str::slug('Other name'),
            'name' => "Other name",
        ], Arr::only($data, ['id', 'slug', 'name']));
    }

    /**
     * @test
     * @group type_person
     * @group type_person-destroy
     * @group destroy
     */
    public function destroyWithSuccess()
    {
        $typePerson = factory(TypePerson::class)->create();
        $this->assertNotNull(TypePerson::first());
        $response = $this->deleteJson(route('jp_realestate.api.type_person.destroy', $typePerson->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.delete')
            ],
            $response->json()
        );
        $this->assertNull(TypePerson::first());
    }

    /**
     * @test
     * @group type_person
     * @group type_person-destroy
     * @group destroy
     */
    public function destroyWithError()
    {
        $person = factory(Person::class)->create();
        $this->assertNotNull(TypePerson::first());
        $response = $this->deleteJson(
            route('jp_realestate.api.type_person.destroy', $person->type_person_id)
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertEquals(
            [
                'error' => true,
                'message' => Terminologies::get('all.resource.error.delete_relationships')
            ],
            $response->json()
        );
        $this->assertNotNull(TypePerson::first());
    }

    /**
     * @test
     * @group type_person
     * @group type_person-validation
     * @group validation
     */
    public function validateDataRequest()
    {
        //$typePerson = factory(TypePerson::class)->create();
        $data = [
            'name' => 'Test name',
        ];
        $response = $this->postJson(route('jp_realestate.api.type_person.store'), $data);
        // dd("Json", $response->json());
        $response->assertStatus(Response::HTTP_CREATED);
        $data['name'] = 'Test name 2';
        $array_validation = [
            ['key' => 'name', 'value' => null],
            ['key' => 'name', 'value' => Str::random(2)],
            ['key' => 'name', 'value' => Str::random(256)],

        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            $aux[$item['key']] = $item['value'];
            $response = $this->postJson(route('jp_realestate.api.type_person.store'), $aux);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $typePerson = TypePerson::first();

        $response = $this->patchJson(
            route('jp_realestate.api.type_person.update', $typePerson->id),
            ["name" => "Test name"]
        );

        $response->assertStatus(Response::HTTP_OK);
    }
}
