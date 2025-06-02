<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Database\Factories\ImagePropertyFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageProperty extends Model
{
    use UsesUuid;
    use HasFactory;

    protected $table = 'image_properties';

    protected $appends = ['way_url'];

    protected $guarded = [];

    public function getWayUrlAttribute()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))->url($this->way);
    }

    public function getThumbnailUrlAttribute()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))->url($this->thumbnail);
    }

    public function urlStorage()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix() . $this->way;
    }

    protected static function newFactory()
    {
        return ImagePropertyFactory::new();
    }
}
