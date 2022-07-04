<?php

namespace Jeffpereira\RealEstate\Models\Traits;

use Illuminate\Support\Str;

trait UpperAttributes
{
    abstract protected function attributesUpper(): array;

    protected static function bootUpperAttributes()
    {
        static::saving(function ($model) {
            collect($model->attributesUpper())->each(function ($attribute) use ($model) {
                $model->{$attribute} = Str::upper($model->{$attribute});
            });
        });
    }
}
