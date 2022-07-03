<?php

namespace Jeffpereira\RealEstate\Http\Resources\Traits;

use Illuminate\Support\Str;

trait ResolverEntities
{

    private function resolverEntity(string $class): string
    {
        $class = Str::replaceFirst(
            "Jeffpereira\RealEstate\Models",
            "Jeffpereira\RealEstate\Http\Resources",
            $class
        );
        return "{$class}Resource";
    }
}
