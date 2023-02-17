<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class ImageProperty extends Model implements HasMedia
{
    use UsesUuid;
    use InteractsWithMedia;

    protected $table = 'image_properties';

    protected $appends = ['way_url'];

    protected $guarded = [];

    public function getWayUrlAttribute()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))->url($this->way);
    }

    public function urlStorage()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix() . $this->way;
    }
}
