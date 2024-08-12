<?php

namespace Jeffpereira\RealEstate\Utilities\Helpers;

use Illuminate\Support\Str;

class RouteHelper
{
    public static function allView()
    {
        $routes = [
            'Imóveis' => 'jp_realestate.property.index',
            'Projetos' => 'jp_realestate.project.index',
            'Tipos' => 'jp_realestate.type.index',
        ];

        return collect($routes)->map(function ($route, $name) {
            return [
                'slug' => Str::slug($name),
                'title' => $name,
                'url' => route($route),
                'path' => route($route, [], false),
            ];
        })->toArray();
    }
}
