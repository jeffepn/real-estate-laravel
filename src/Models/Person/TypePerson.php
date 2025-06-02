<?php

namespace Jeffpereira\RealEstate\Models\Person;

use Database\Factories\TypePersonFactory;
use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypePerson extends Model
{
    use UsesUuid;
    use HasFactory;
    use SetSlugByName;

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return TypePersonFactory::new();
    }
}
