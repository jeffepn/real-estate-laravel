<?php

namespace Jeffpereira\RealEstate;

use Illuminate\Support\Facades\App;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;

class RealEstateBladeDirective
{
    public static function mainStyles(): string
    {
        $assets[] = self::isDebug() || self::environmentIsDev() ? '<!-- Realestatelaravel Styles -->' : '';
        $version = AppSettingsEnum::VERSION_PACKAGE;
        $assets[] = self::environmentIsDev()
            ? "<link rel='stylesheet' href='http://localhost:9099/realestatelaravel/css/realestatelaravel.css' />"
            : "<link rel='stylesheet' href='https://cdn.jsdelivr.net/gh/jeffepereira/real-estate-laravel@{$version}/dist/css/realestatelaravel.css' />";

        return implode('', $assets);
    }

    public static function mainScripts(): string
    {
        $assets[] = self::isDebug() || self::environmentIsDev() ? '<!-- Realestatelaravel Scripts -->' : '';
        $version = AppSettingsEnum::VERSION_PACKAGE;
        $assets[] = self::environmentIsDev()
            ? "<script src='http://localhost:9099/realestatelaravel/js/realestatelaravel.js' type='text/javascript'></script>"
            : "<script src='https://cdn.jsdelivr.net/gh/jeffepereira/real-estate-laravel@{$version}/dist/js/realestatelaravel.js' type='text/javascript'></script>";

        return implode('', $assets);
    }

    private static function environmentIsDev(): bool
    {
        return App::environment(['devpackage']);
    }

    private static function isDebug(): bool
    {
        return config('app.debug');
    }
}
