<?php

namespace Jeffpereira\RealEstate\Models\Traits;

trait SetSlug
{
    abstract public function generateSlug(): string;

    protected static function bootSetSlug()
    {
        static::saving(function ($model) {
            $model->slug = $model->generateSlug();
        });
    }
}
