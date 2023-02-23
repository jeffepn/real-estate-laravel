<?php

namespace Jeffpereira\RealEstate\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

class Image extends Model
{
    use UsesUuid;

    public $table = 'images_realestate';

    protected $fillable = [
        'way', 'thumbnail', 'alt', 'title', 'description', 'author',
    ];

    protected $appends = ['way_url'];

    public function getWayUrlAttribute()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))->url($this->way);
    }

    public function getThumbnailUrlAttribute()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))->url($this->thumbnail);
    }
}
