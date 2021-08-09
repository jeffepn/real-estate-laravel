<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Support\Str;

class Situation extends Model
{
    use UsesUuid, SetSlug;

    protected $guarded = [];


    protected function generateSlug()
    {
        return Str::slug($this->name);
    }
}
