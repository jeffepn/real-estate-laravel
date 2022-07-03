<?php

namespace Jeffpereira\RealEstate\Tests\Feature\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Person\TypePerson;
use Jeffpereira\RealEstate\Models\Project\Project;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class PersonTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const FORMAT_RESOURCE = [
        'type', 'id',
        'attributes' => [
            'slug', "name", "bio"
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
     * @group person
     * @group person-index
     * @group index
     */
    public function verifyFormatReturnIndex()
    {
        factory(Person::class, 20)->create();
        $response = $this->getJson(route('jp_realestate.api.person.index'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_INDEX, $response->json());
    }

    /**
     * @test
     * @group person
     * @group person-index
     * @group index
     */
    public function verifyDataReturnIndex()
    {
        factory(Person::class, 20)->create();

        $response = $this->getJson(route('jp_realestate.api.person.index'));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals([
            'data' => Person::orderBy('name')->get()->map(function ($person) {
                return [
                    'type' => 'person',
                    'id' => $person->id,
                    'attributes' => Arr::only($person->toArray(), ['slug', 'name', 'bio']),
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
     * @group person
     * @group person-index
     * @group index
     */
    public function verifyDataReturnIndexWithType()
    {
        factory(TypePerson::class, 2)->create()->each(function ($typePerson) {
            factory(Person::class, 2)->create(['type_person_id' => $typePerson->id]);
        });

        $response = $this->getJson(route('jp_realestate.api.person.index') . '?with=type');

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals([
            'data' => Person::orderBy('name')->get()->map(function ($person) {
                return [
                    'type' => 'person',
                    'id' => $person->id,
                    'attributes' => Arr::only($person->toArray(), ['slug', 'name', 'bio']),
                    'relationships' => [
                        'type' => Arr::add(self::DARA_RESOURCE_TYPE, 'data.id', $person->type_person_id)
                    ],
                ];
            })->toArray(),
            'included' => TypePerson::all()->map(function ($type) {
                return [
                    'type' => 'type_person', 'id' => $type->id,
                    'attributes' => Arr::only($type->toArray(), ['slug', 'name']),
                    'relationships' => []
                ];
            })->unique()->sortBy('id')->values()->toArray(),
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group person
     * @group person-show
     * @group show
     */
    public function verifyFormatReturnShow()
    {
        $person = factory(Person::class)->create();
        $person->refresh();

        $response = $this->getJson(route('jp_realestate.api.person.show', $person->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_SHOW, $response->json());
    }

    /**
     * @test
     * @group person
     * @group person-show
     * @group show
     */
    public function verifyDataReturnShow()
    {
        $person = factory(Person::class)->create();
        $person->refresh();

        $response = $this->getJson(route('jp_realestate.api.person.show', $person->id));

        $response->assertStatus(Response::HTTP_OK);
        $format = self::DARA_RESOURCE;
        $format['data'] = [
            'type' => 'person', 'id' => $person->id,
            'attributes' => Arr::only($person->toArray(), ['slug', 'name', 'bio']),
            'relationships' => [],
        ];
        $this->assertEquals($format, $response->json());
    }

    /**
     * @test
     * @group person
     * @group person-show
     * @group show
     */
    public function verifyDataReturnShowWithType()
    {
        $person = factory(Person::class)->create();
        $person->refresh();

        $response = $this->getJson(
            route('jp_realestate.api.person.show', $person->id) . "?with=type"
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals([
            'data' => [
                'type' => 'person', 'id' => $person->id,
                'attributes' => Arr::only($person->toArray(), ['slug', 'name', 'bio']),
                'relationships' => [
                    'type' => Arr::add(self::DARA_RESOURCE_TYPE, 'data.id', $person->type_person_id)
                ],
            ],
            'included' => [
                [
                    'type' => 'type_person', 'id' => $person->type_person_id,
                    'attributes' => Arr::only($person->type->toArray(), ['slug', 'name']),
                    'relationships' => []
                ]
            ],
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group person
     * @group person-store
     * @group store
     */
    public function storeWithSuccess()
    {
        $typePerson = factory(TypePerson::class)->create(['name' => "Name of type person"]);
        $dataStore = [
            'type_person_id' => $typePerson->id,
            'name' =>  $this->faker->name(),
            'bio' => $this->faker->sentence(20)
        ];

        $response = $this->postJson(
            route('jp_realestate.api.person.store'),
            $dataStore
        );
        $person = Person::first();

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_SHOW);
        $this->assertEquals($dataStore, Arr::only($person->toArray(), ['type_person_id', 'name', 'bio']));
        $this->assertEquals('Name of type person', $person->type->name);
    }

    /**
     * @test
     * @group person
     * @group person-update
     * @group update
     */
    public function updateWithSuccess()
    {
        $person = factory(Person::class)->create();

        $response = $this->patchJson(route('jp_realestate.api.person.update', $person->id), [
            'name' => "Other name",
            'bio' => 'test bio edit',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.save')
            ],
            $response->json()
        );
        $data = Person::find($person->id)->toArray();
        $this->assertEquals([
            'id' => $person->id,
            'type_person_id' => $person->type_person_id,
            'slug' => Str::slug('Other name'),
            'name' => "Other name",
            'bio' => 'test bio edit',
        ], Arr::only($data, ['id', 'type_person_id', 'slug', 'name', 'bio']));
    }

    /**
     * @test
     * @group person
     * @group person-destroy
     * @group destroy
     */
    public function destroyWithSuccess()
    {
        $person = factory(Person::class)->create();
        $this->assertNotNull(Person::first());
        $response = $this->deleteJson(route('jp_realestate.api.person.destroy', $person->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.delete')
            ],
            $response->json()
        );
        $this->assertNull(Person::first());
    }

    /**
     * @test
     * @group person
     * @group person-destroy
     * @group destroy
     */
    public function destroyWithError()
    {
        $project = factory(Project::class)->create();
        $this->assertNotNull(Person::first());
        $response = $this->deleteJson(route('jp_realestate.api.person.destroy', $project->person_id));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertEquals(
            [
                'error' => true,
                'message' => Terminologies::get('all.resource.error.delete_relationships')
            ],
            $response->json()
        );
        $this->assertNotNull(Person::first());
    }

    /**
     * @test
     * @group person
     * @group person-validation
     * @group validation
     */
    public function validateDataRequest()
    {
        $typePerson = factory(TypePerson::class)->create();
        $data = [
            'type_person_id' =>  $typePerson->id,
            'name' => 'Test name',
            'bio' => 'test bio',
        ];
        $response = $this->postJson(route('jp_realestate.api.person.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $data['name'] = 'Test name 2';
        $array_validation = [
            ['key' => 'name', 'value' => null],
            ['key' => 'name', 'value' => Str::random(2)],
            ['key' => 'name', 'value' => Str::random(256)],
            ['key' => 'bio', 'value' => Str::random(501)],
            ['key' => 'type_person_id', 'value' => null],
            ['key' => 'type_person_id', 'value' => Str::uuid()],

        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            $aux[$item['key']] = $item['value'];
            $response = $this->postJson(route('jp_realestate.api.person.store'), $aux);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $person = Person::first();

        $response = $this->patchJson(
            route('jp_realestate.api.person.update', $person->id),
            ["name" => "Test name"]
        );

        $response->assertStatus(Response::HTTP_OK);
    }
}
