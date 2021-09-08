<?php

namespace Jeffpereira\RealEstate\Utilities\Helpers;

class RouteHelper
{
    public static function allView()
    {
        return [
            [
                'slug' => 'imoveis',
                'title' => 'Imóveis',
                'url' => route('jp_realestate.property.list'),
                'path' => route('jp_realestate.property.list', [], false)
            ]
        ];
    }
}
