<?php

namespace Jeffpereira\RealEstate\Models\Traits;

use Illuminate\Support\Str;

trait UpperAttributes
{
    protected static function bootUpperAttributes()
    {
        static::saving(function ($model) {
            collect($model->attributesUpper())->each(function ($attribute) use ($model) {
                $model->$attribute = Str::upper($model->$attribute);
            });
        });
    }

    abstract protected function attributesUpper(): array;
}
