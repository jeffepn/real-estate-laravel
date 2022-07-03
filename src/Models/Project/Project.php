<?php

namespace Jeffpereira\RealEstate\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\Project\Relationships;
use Jeffpereira\RealEstate\Models\Traits\Project\Scopes;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Project extends Model
{
    use UsesUuid, SetSlugByName, Relationships, Scopes;

    protected $fillable = [
        'person_id', 'name', 'content'
    ];
}
