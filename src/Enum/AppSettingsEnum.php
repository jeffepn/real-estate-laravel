<?php

namespace Jeffpereira\RealEstate\Enum;

class AppSettingsEnum
{
    const WATTERMARK_IMAGE_PROPERTY = 'wattermark_image_property';

    public static function getAll(): array
    {
        return [
            self::WATTERMARK_IMAGE_PROPERTY
        ];
    }
}
