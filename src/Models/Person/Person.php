<?php

namespace Jeffpereira\RealEstate\Models\Person;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\Person\Relationships;
use Jeffpereira\RealEstate\Models\Traits\Person\Scopes;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Person extends Model
{
    use UsesUuid;
    use SetSlugByName;
    use Relationships;
    use Scopes;

    protected $fillable = ['type_person_id', 'name', 'bio'];
}
