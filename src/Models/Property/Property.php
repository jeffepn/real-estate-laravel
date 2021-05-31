<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Property extends Model
{
    use UsesUuid;

    protected $guarded = [];
}
