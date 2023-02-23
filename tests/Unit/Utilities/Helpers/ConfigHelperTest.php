<?php

namespace Jeffpereira\RealEstate\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Jeffpereira\RealEstate\Enum\DirectoryImagesEnum;
use Jeffpereira\RealEstate\Tests\TestCase;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

class ConfigHelperTest extends TestCase
{
    use RefreshDatabase;

    public function testGetUseTemplate()
    {
        $this->assertEquals(true, ConfigHelper::get('use_template'));
    }

    public function testGetOptmizeProperties()
    {
        $directoryEnity = DirectoryImagesEnum::PROPERTY;
        $this->assertEquals(true, ConfigHelper::get("filesystem.entities.{$directoryEnity}.optmize"));
        $this->assertEquals($directoryEnity, ConfigHelper::get("filesystem.entities.{$directoryEnity}.path"));
    }

    public function testGetOptmizeProjects()
    {
        $directoryEnity = DirectoryImagesEnum::PROJECT;
        $this->assertEquals(true, ConfigHelper::get("filesystem.entities.{$directoryEnity}.optmize"));
        $this->assertEquals($directoryEnity, ConfigHelper::get("filesystem.entities.{$directoryEnity}.path"));
    }
}
