<?php

namespace Jeffpereira\RealEstate\Utilities\Helpers;

use Illuminate\Support\Arr;
use Jeffpereira\RealEstate\Enum\DirectoryImagesEnum;

class ConfigHelper
{
    public const DEFAULT_CONFIGS = [
        'use_template' => true,
        'template' => null,
        'section_content' => 'content',
        'middleware.web' => 'web',
        'middleware.api' => 'api',
        'filesystem.disk' => 'public',
        'filesystem.entities.properties.path' => DirectoryImagesEnum::PROPERTY,
        'filesystem.entities.properties.optmize' => true,
        'filesystem.entities.projects.path' => DirectoryImagesEnum::PROJECT,
        'filesystem.entities.projects.optmize' => true,
        'url_home' => null,
    ];

    /**
     * Get config laravelrealestate to dot anotation
     * Example:
     *
     * @param string $config
     * @return mixed
     */
    public static function get(string $config)
    {
        $configRealEstate = config('realestatelaravel');

        return Arr::get(
            $configRealEstate,
            $config,
            Arr::get(self::DEFAULT_CONFIGS, $config)
        );
    }
}
