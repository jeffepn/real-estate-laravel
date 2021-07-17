<?php

namespace Jeffpereira\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Banner extends Model
{
    use UsesUuid;

    protected $fillable = ['way', 'title', 'content', 'link'];
    // protected $guarded = [];

    protected $appends = ['way_url'];

    public function getWayUrlAttribute()
    {
        return Storage::disk(config('realestatelaravel.filesystem.disk'))->url($this->way);
    }
}
