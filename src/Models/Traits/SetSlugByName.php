<?php

namespace Jeffpereira\RealEstate\Models\Traits;

use Illuminate\Support\Str;

trait SetSlugByName
{
    protected static function bootSetSlugByName()
    {
        static::saving(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }
}
