<?php

namespace Jeffpereira\RealEstate\Enum;

use Illuminate\Support\Arr;

class AppSettingsEnum
{
    public const VERSION_PACKAGE = '1.10.2';
    public const WATTERMARK_IMAGE_PROPERTY = 'wattermark_image_property';
    public const WATTERMARK_IMAGE_PROJECT = 'wattermark_image_project';
    private const SLUG_PROPERTY = 'properties';
    private const SLUG_PROJECT = 'projects';

    private const TRANSLATE = [
        self::SLUG_PROPERTY => self::WATTERMARK_IMAGE_PROPERTY,
        self::SLUG_PROJECT => self::WATTERMARK_IMAGE_PROJECT,
    ];

    public static function getAll(): array
    {
        return [
            self::WATTERMARK_IMAGE_PROPERTY,
            self::WATTERMARK_IMAGE_PROJECT,
        ];
    }

    public static function translateEntity(string $slug)
    {
        return Arr::get(self::TRANSLATE, $slug);
    }
}
