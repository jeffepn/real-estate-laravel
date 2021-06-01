<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class SubType extends Model
{
    use UsesUuid, SetSlug;

    protected function generateSlug()
    {
        return Str::slug($this->name);
    }
}
