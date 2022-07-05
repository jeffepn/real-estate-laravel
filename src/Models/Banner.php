<?php

namespace Jeffpereira\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

class Banner extends Model
{
    use UsesUuid;

    protected $fillable = ['way', 'title', 'content', 'link'];
    // protected $guarded = [];

    protected $appends = ['way_url'];

    public function getWayUrlAttribute()
    {
        return Storage::disk(ConfigHelper::get('filesystem.disk'))->url($this->way);
    }
}
