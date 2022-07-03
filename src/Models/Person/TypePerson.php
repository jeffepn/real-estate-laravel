<?php

namespace Jeffpereira\RealEstate\Models\Person;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class TypePerson extends Model
{
    use UsesUuid, SetSlugByName;
}
