<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class ImageProperty extends Model
{
    use UsesUuid;

    const CONFIG_DISK = "realestatelaravel.filesystem.entities.properties.disk";

    protected $table = "image_properties";

    protected $appends = ['way_url'];

    protected $guarded = [];

    public function getWayUrlAttribute()
    {
        return Storage::disk(config(self::CONFIG_DISK))->url($this->way);
    }
}
