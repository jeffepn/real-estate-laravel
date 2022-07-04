<?php

namespace Jeffpereira\RealEstate;

use Illuminate\Support\Facades\App;

class RealEstateBladeDirective
{
    public static function mainStyles(): string
    {
        $assets[] = self::isDebug() || self::environmentIsDev() ? '<!-- Realestatelaravel Styles -->' : '';
        $assets[] = self::environmentIsDev()
            ? "<link href='http://0.0.0.0:9099/realestatelaravel/css/realestatelaravel.css' />"
            : "<link href='https://cdn.jsdelivr.net/gh/jeffepereira/real-estate-laravel@1/dist/css/realestatelaravel.css' />";

        return implode('', $assets);
    }

    public static function mainScripts(): string
    {
        $assets[] = self::isDebug() || self::environmentIsDev() ? '<!-- Realestatelaravel Scripts -->' : '';
        $assets[] = self::environmentIsDev()
            ? "<script src='http://0.0.0.0:9099/realestatelaravel/js/realestatelaravel.js'></script>"
            : "<script src='https://cdn.jsdelivr.net/gh/jeffepereira/real-estate-laravel@1/dist/js/realestatelaravel.js'></script>";

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
