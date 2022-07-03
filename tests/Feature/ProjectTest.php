<?php

namespace Jeffpereira\RealEstate\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Person\TypePerson;
use Jeffpereira\RealEstate\Models\Project\Project;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const FORMAT_RESOURCE = [
        'type', 'id',
        'attributes' => [
            'slug', "name", "content"
        ],
        'relationships' => [],
    ];

    /**
     * @tests
     * @group project
     * @group project-index
     * @group index
     */
    public function verifyFormatReturnIndex()
    {
        factory(Project::class, 20)->create();
        $response = $this->getJson(route('jp_realestate.api.project.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'type', 'id',
                    'attributes' => [
                        'slug', "name", "content"
                    ],
                    'relationships' => [],
                ]
            ],
            'included'
        ], $response->json());
    }

    /**
     * @test
     * @group project
     * @group project-index
     * @group index
     */
    public function verifyFormatReturnIndexWithResponsible()
    {
        factory(TypePerson::class, 2)->create()->each(function ($typePerson) {

            factory(Person::class, 2)->create(['type_person_id' => $typePerson->id])
                ->each(function ($person) {
                    factory(Project::class, 5)->create(['person_id' => $person->id]);
                });
        });
        $response = $this->getJson(route('jp_realestate.api.project.index') . '?with=responsible,responsible.type');

        $response->assertStatus(200);
        $formatResourceRelationships = self::FORMAT_RESOURCE;
        $formatResourceRelationships['relationships'] = ['responsible'];
        $response->assertJsonStructure([
            'data' => [$formatResourceRelationships],
            'included', 'error', 'message'
        ], $response->json());
        $includes = collect();
        $projects = Project::with('responsible', 'responsible.type')->get();
        $projects->pluck('responsible')->unique()->sortBy('id')->each(function ($person) use ($includes) {
            $includes->push([
                'type' => 'person',
                'id' => $person->id,
                'attributes' => ['slug' => $person->slug, 'name' => $person->name, 'bio' => $person->bio],
                'relationships' => [
                    'type' => [
                        'data ' => [
                            'type' => 'type_person',
                            'id' => $person->type_person_id,
                        ]
                    ]
                ]
            ]);
        });
        $projects->pluck('responsible.type')->unique()->sortBy('id')->each(function ($typePerson) use ($includes) {
            $includes->push([
                'type' => 'type_person',
                'id' => $typePerson->id,
                'attributes' => ['slug' => $typePerson->slug, 'name' => $typePerson->name],
                'relationships' => []
            ]);
        });
        $this->assertEqualsCanonicalizing([
            'data' => Project::orderBy('name')->get()->map(function ($project) {
                return [
                    'type' => 'project',
                    'id' => $project->id,
                    'attributes' => [
                        'slug' => $project->slug,
                        'name' => $project->name,
                        'content' => $project->content,
                    ],
                    'relationships' => [
                        'responsible' => [
                            'data' => [
                                'type' => 'person',
                                'id' => $project->person_id
                            ]
                        ]
                    ],
                ];
            })->toArray(),
            'included' => $includes->toArray(),
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group project
     * @group project-show
     * @group show
     */
    public function verifyFormatReturnShow()
    {
        $project = factory(Project::class)->create();
        $project->refresh();
        $response = $this->getJson(route('jp_realestate.api.project.show', $project->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => self::FORMAT_RESOURCE,
            'included', 'error', 'message'
        ], $response->json());
        $this->assertEquals([
            'data' => [
                'type' => 'project',
                'id' => $project->id,
                'attributes' => [
                    'slug' => $project->slug,
                    'name' => $project->name,
                    'content' => $project->content,
                ],
                'relationships' => [],
            ],
            'included' => [],
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group project
     * @group project-show
     * @group show
     */
    public function verifyFormatReturnShowWithResponsible()
    {
        $project = factory(Project::class)->create();
        $project->refresh();
        $response = $this->getJson(
            route('jp_realestate.api.project.show', $project->id) . "?with=responsible"
        );

        $response->assertStatus(200);
        $formatResourceRelationships = self::FORMAT_RESOURCE;
        $formatResourceRelationships['relationships'] = ['responsible'];
        $response->assertJsonStructure([
            "data" => $formatResourceRelationships,
            'included', 'error', 'message'
        ], $response->json());
        $this->assertEquals([
            'data' => [
                'type' => 'project',
                'id' => $project->id,
                'attributes' => [
                    'slug' => $project->slug,
                    'name' => $project->name,
                    'content' => $project->content,
                ],
                'relationships' => [
                    'responsible' => [
                        'data' => [
                            'type' => 'person',
                            'id' => $project->person_id
                        ]
                    ]
                ],
            ],
            'included' => [
                [
                    'type' => 'person',
                    'id' => $project->person_id,
                    'attributes' => [
                        'slug' => $project->responsible->slug,
                        'name' => $project->responsible->name,
                        'bio' => $project->responsible->bio,
                    ],
                    'relationships' => []
                ]
            ],
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group project
     * @group project-show
     * @group show
     */
    public function verifyFormatReturnShowWithResponsibleAndType()
    {
        $project = factory(Project::class)->create();
        $project->refresh();
        $response = $this->getJson(
            route('jp_realestate.api.project.show', $project->id) . "?with=responsible,responsible.type"
        );

        $response->assertStatus(200);
        $formatResourceRelationships = self::FORMAT_RESOURCE;
        $formatResourceRelationships['relationships'] = ['responsible'];
        $response->assertJsonStructure([
            "data" => $formatResourceRelationships,
            'included', 'error', 'message'
        ], $response->json());
        $this->assertEquals([
            'data' => [
                'type' => 'project',
                'id' => $project->id,
                'attributes' => [
                    'slug' => $project->slug,
                    'name' => $project->name,
                    'content' => $project->content,
                ],
                'relationships' => [
                    'responsible' => [
                        'data' => [
                            'type' => 'person',
                            'id' => $project->person_id
                        ]
                    ]
                ],
            ],
            'included' => [
                [
                    'type' => 'person',
                    'id' => $project->person_id,
                    'attributes' => [
                        'slug' => $project->responsible->slug,
                        'name' => $project->responsible->name,
                        'bio' => $project->responsible->bio,
                    ],
                    'relationships' => [
                        'type' => [
                            'data' => [
                                'type' => 'type_person',
                                'id' => $project->responsible->type_person_id
                            ]
                        ]
                    ]
                ],
                [
                    'type' => 'type_person',
                    'id' => $project->responsible->type_person_id,
                    'attributes' => [
                        'slug' => $project->responsible->type->slug,
                        'name' => $project->responsible->type->name,
                    ],
                    'relationships' => []
                ]
            ],
            'error' => false,
            'message' => ''
        ], $response->json());
    }

    /**
     * @test
     * @group project
     * @group project-store
     * @group store
     */
    public function storeWithSuccess()
    {
        $person = factory(Person::class)->create(['name' => "Name of person"]);
        $name = $this->faker->name();
        $content = $this->faker->sentence(20);
        $response = $this->postJson(
            route('jp_realestate.api.project.store'),
            [
                'person_id' => $person->id,
                'name' => $name,
                'content' => $content,
                'with' => 'responsible'
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            "data" => [
                'type', 'id',
                'attributes' => ['slug', "name", "content"],
                'relationships' => ['responsible'],
            ],
            'included', 'error', 'message'
        ]);
        $project = Project::first();

        $project->refresh();
        $this->assertEquals([
            'data' => [
                'type' => 'project',
                'id' => $project->id,
                'attributes' => [
                    'slug' => $project->slug, 'name' => $project->name, 'content' => $project->content,
                ],
                'relationships' => [
                    'responsible' => ['data' => ['type' => 'person', 'id' => $project->person_id]],
                ],
            ],
            'included' => [
                [
                    'type' => 'person',
                    'id' => $project->person_id,
                    'attributes' => [
                        'slug' => $project->responsible->slug,
                        'name' => $project->responsible->name,
                        'bio' => $project->responsible->bio,
                    ],
                    'relationships' => []
                ]
            ],
            'error' => false,
            'message' => Terminologies::get('all.common.save_data')
        ], $response->json());
        $this->assertEquals('Name of person', $project->responsible->name);
    }

    /**
     * @test
     * @group project
     * @group project-update
     * @group update
     */
    public function updateWithSuccess()
    {
        $project = factory(Project::class)->create();
        $response = $this->patchJson(route('jp_realestate.api.project.update', $project->id), [
            'name' => "Other name",
            'content' => 'test content edit',
        ]);
        $response->assertStatus(200);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.save')
            ],
            $response->json()
        );
        $data = Project::find($project->id)->toArray();
        unset($data['created_at']);
        unset($data['updated_at']);
        $this->assertEquals([
            'id' => $project->id,
            'slug' => Str::slug('Other name'),
            'name' => "Other name",
            'person_id' => $project->person_id,
            'content' => 'test content edit',
        ], $data);
    }

    /**
     * @test
     * @group project
     * @group project-destroy
     * @group destroy
     */
    public function destroyWithSuccess()
    {
        $project = factory(Project::class)->create();
        $this->assertNotNull(Project::first());
        $response = $this->deleteJson(route('jp_realestate.api.project.destroy', $project->id));
        $response->assertStatus(200);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.delete')
            ],
            $response->json()
        );
        $this->assertNull(Project::first());
    }

    /**
     * @test
     * @group project
     * @group project-validation
     * @group validation
     */
    public function validateDataRequest()
    {
        $person = factory(Person::class)->create();
        $data = [
            'name' => 'Test name',
            'person_id' =>  $person->id,
            'content' => 'test content', 'items' => 'test items',
        ];
        $response = $this->postJson(route('jp_realestate.api.project.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $data['name'] = 'Test name 2';
        $array_validation = [
            ['key' => 'name', 'value' => null],
            ['key' => 'name', 'value' => 'Test name'],
            ['key' => 'name', 'value' => Str::random(2)],
            ['key' => 'name', 'value' => Str::random(151)],
            ['key' => 'person_id', 'value' => null],
            ['key' => 'person_id', 'value' => Str::uuid()],

        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            $aux[$item['key']] = $item['value'];
            $response = $this->postJson(route('jp_realestate.api.project.store'), $aux);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $project = Project::first();

        $response = $this->patchJson(
            route('jp_realestate.api.project.update', $project->id),
            ["name" => "Test name",]
        );
        $response->assertStatus(Response::HTTP_OK);
    }
}
