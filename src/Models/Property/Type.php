<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;

class Type extends Model
{
    use UsesUuid, SetSlug;

    protected function generateSlug()
    {
        return Str::slug($this->name);
    }
}
