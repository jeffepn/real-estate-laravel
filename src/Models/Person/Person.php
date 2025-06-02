<?php

namespace Jeffpereira\RealEstate\Models\Person;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\Person\Relationships;
use Jeffpereira\RealEstate\Models\Traits\Person\Scopes;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    use UsesUuid;
    use HasFactory;
    use SetSlugByName;
    use Relationships;
    use Scopes;

    protected $fillable = ['type_person_id', 'name', 'bio'];

    protected static function newFactory()
    {
        return PersonFactory::new();
    }
}
