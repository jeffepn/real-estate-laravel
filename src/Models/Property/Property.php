<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Property extends Model
{
    use UsesUuid;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($property) {
            $property->slug = $property->slug ?: static::genereateSlug();
        });
    }




    private static function genereateSlug()
    {
        return '';
    }
}
