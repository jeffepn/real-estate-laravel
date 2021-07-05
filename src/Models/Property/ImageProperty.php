<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class ImageProperty extends Model
{
    use UsesUuid;

    protected $table = "image_properties";

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($image) {
            Storage::disk(config('realestatelaravel.filesystem.disk'))->delete($image->way);
        });
    }
}
