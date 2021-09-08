<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class ImageProperty extends Model
{
    use UsesUuid;

    protected $table = "image_properties";

    protected $appends = ['way_url'];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($image) {
            Storage::disk(config('realestatelaravel.filesystem.entities.properties.disk'))->delete($image->way);
        });
        self::creating(function ($image) {
            $image->order = static::where('property_id', $image->property_id)->max('order') + 1;
        });
    }

    public function getWayUrlAttribute()
    {
        return Storage::disk(config('realestatelaravel.filesystem.entities.properties.disk'))->url($this->way);
    }
}
