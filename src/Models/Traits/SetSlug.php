<?php

namespace Jeffpereira\RealEstate\Models\Traits;

trait SetSlug
{
    protected static function bootSetSlug()
    {
        static::saving(function ($model) {
            $model->slug = $model->generateSlug();
        });
    }

    abstract public function generateSlug();
}
