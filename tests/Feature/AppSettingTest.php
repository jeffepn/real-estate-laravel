<?php

namespace Tests\Feature;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->api = route('jp_realestate.app_setting.store');
    }


    /**
     * @test
     * @group store
     * @group now
     */
    public function testStoreWatterMark()
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
     * @group validation
     */
    public function testValidationWatterMark()
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
