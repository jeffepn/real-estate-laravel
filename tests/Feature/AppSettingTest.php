<?php

namespace Tests\Feature;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;
use Jeffpereira\RealEstate\Tests\TestCase;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\AppSettings;

class AppSettingTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private $api;

    /**
     * @var Storage
     */
    private $storage;

    public function setUp(): void
    {
        parent::setUp();
        $this->api = route('jp_realestate.app_setting.store');
        Storage::fake(config('realestatelaravel.filesystem.disk'));
        $this->storage = Storage::disk(config('realestatelaravel.filesystem.disk'));
    }


    /**
     * @test
     * @group app-setting
     * @group store
     * @group store-app-setting
     */
    public function storeSettingWatterMark()
    {
        Storage::fake(config('realestatelaravel.filesystem.disk'));
        $data = [
            'name' => AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY,
            'image_watter' => UploadedFile::fake()->image('watter.jpg')
        ];
        $response = $this->postJson($this->api, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $appSettings = AppSettings::first();
        $this->assertNotNull($appSettings);
        $this->assertEquals(AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY, $appSettings->name);
        $this->assertNotNull($appSettings->value['image']);
    }
    /**
     * @test
     * @group app-setting
     * @group store
     */
    public function updateSettingWatterMark()
    {
        $appSettings = AppSettings::create([
            'name' => AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY,
            'value' => ['image' => $this->faker->imageUrl()]
        ]);
        Storage::fake(config('realestatelaravel.filesystem.disk'));
        $data = [
            'name' => AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY,
            'image_watter' => UploadedFile::fake()->image('watter.jpg')
        ];
        $response = $this->patchJson($this->api . "/" . $appSettings->id, $data);
        $response->assertStatus(Response::HTTP_OK);
        $appSettingsUpdated = AppSettings::first();
        $this->assertNotEquals($appSettings->value['image'], $appSettingsUpdated->value['image']);
    }

    /**
     * @test
     * @group app-setting
     * @group destroy
     * @group now
     */
    public function destroySetting()
    {
        $appSettings = AppSettings::create([
            'name' => AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY,
            'value' => [
                'image' =>  $this->storage
                    ->putFileAs(
                        'images',
                        UploadedFile::fake()->image('watter.jpg'),
                        'watter.jpg'
                    )
            ]
        ]);
        $this->assertNotNull(AppSettings::first());
        $this->storage->assertExists('images/watter.jpg');
        $response = $this->deleteJson($this->api . "/" . $appSettings->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertNull(AppSettings::first());
        $this->storage->assertMissing('images/watter.jpg');
    }

    /**
     * @test
     * @group app-setting
     * @group validation
     */
    public function validationSettingWatterMark()
    {
        Storage::fake(config('realestatelaravel.filesystem.disk'));
        $data = [
            'name' => AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY,
            'image_watter' => UploadedFile::fake()->image('watter.jpg')
        ];
        $response = $this->postJson($this->api, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        // Test unique name
        $response = $this->postJson($this->api, $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        AppSettings::whereName(AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY)->delete();
        $array_validation = [
            ['key' => 'name', 'value' => null],
            ['key' => 'name', 'value' => Str::random('257')],
            ['key' => 'name', 'value' => 'config_qualquer'],
            ['key' => 'image_watter', 'value' => null],
            ['key' => 'image_watter', 'value' => UploadedFile::fake()->image('watter.gif')],
            ['key' => 'image_watter', 'value' => UploadedFile::fake()->image('watter.jpg')->size(301)],
        ];
        foreach ($array_validation as $item) {
            $aux = $data;
            $aux[$item['key']] = $item['value'];
            $response = $this->postJson($this->api, $aux);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
