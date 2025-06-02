<?php

namespace Jeffpereira\RealEstate\Tests\Feature\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Project\ImageProject;
use Jeffpereira\RealEstate\Models\Project\Project;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class ProjectImageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public const FORMAT_RESOURCE = [
        'type', 'id',
        'attributes' => [
            'slug', 'name', 'content',
        ],
        'relationships' => [],
    ];
    public const FORMAT_RESOURCE_INDEX = [
        'data' => [
            [
                'type', 'id',
                'attributes' => [
                    'way', 'alt', 'title', 'description', 'author',
                ],
                'relationships' => [],
            ],
        ],
        'included', 'error', 'message',
    ];

    /**
     * @test
     * @group image_project
     * @group image_project-index
     * @group index
     */
    public function verifyFormatReturnIndex()
    {
        $project = Project::factory()->create();
        ImageProject::factory(10)->create(['project_id' => $project->id]);

        $response = $this->getJson(
            route('jp_realestate.api.image_project.index') . "?project_id={$project->id}"
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_INDEX, $response->json());
    }

    /**
     * @test
     * @group image_project
     * @group image_project-store
     * @group store
     */
    public function storeWithSuccess()
    {
        $project = Project::factory()->create();
        $response = $this->postJson(
            route('jp_realestate.api.image_project.store'),
            [
                'project_id' => $project->id,
                'images' => [
                    [
                        'image' => UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(100),
                        'alt' => 'teste',
                    ],
                ],
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::FORMAT_RESOURCE_INDEX);
        $project = Project::first();

        $project->refresh();
        $this->assertEquals([
            'data' => ImageProject::all()->map(function ($imageProject) {
                return [
                    'type' => 'image_project',
                    'id' => $imageProject->id,
                    'attributes' => array_merge(
                        Arr::only(
                            $imageProject->image->toArray(),
                            ['alt', 'title', 'description', 'author']
                        ),
                        [
                            'way' => $imageProject->image->way_url,
                            'thumbnail' => $imageProject->image->thumbnail_url,
                        ]
                    ),
                    'relationships' => [],
                ];
            })->toArray(),
            'included' => [],
            'error' => false,
            'message' => Terminologies::get('all.resource.success.save'),
        ], $response->json());
    }

    /**
     * @test
     * @group image_project
     * @group image_project-destroy
     * @group destroy
     */
    public function destroyWithSuccess()
    {
        $imageProject = ImageProject::factory()->create();
        $this->assertNotNull(ImageProject::first());
        $response = $this->deleteJson(route('jp_realestate.api.image_project.destroy', $imageProject->id));
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(
            [
                'error' => false,
                'message' => Terminologies::get('all.resource.success.delete'),
            ],
            $response->json()
        );
        $this->assertNull(ImageProject::first());
    }

    /**
     * @test
     * @group image_project
     * @group image_project-validation
     * @group validation
     */
    public function validateDataRequest()
    {
        $project = Project::factory()->create();
        $data = [
            'project_id' => $project->id,
            'images' => [
                [
                    'image' => UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(100),
                    'alt' => $this->faker->sentence(4),
                    'title' => $this->faker->sentence(4),
                    'description' => $this->faker->sentence(4),
                    'author' => $this->faker->sentence(4),
                ],
            ],
        ];
        $response = $this->postJson(route('jp_realestate.api.image_project.store'), $data);

        $response->assertStatus(Response::HTTP_OK);

        $array_validation = [
            ['key' => 'images.0.image', 'value' => null],
            ['key' => 'images.0.image', 'value' => Str::random(4)],
            ['key' => 'images.0.image', 'value' => UploadedFile::fake()->create('test.pdf', 400)],
            ['key' => 'images.0.alt', 'value' => Str::random(256)],
            ['key' => 'images.0.title', 'value' => Str::random(256)],
            ['key' => 'images.0.description', 'value' => Str::random(256)],
            ['key' => 'images.0.author', 'value' => Str::random(256)],
        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            Arr::set($aux, $item['key'], $item['value']);
            $response = $this->postJson(route('jp_realestate.api.image_project.store'), $aux);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
