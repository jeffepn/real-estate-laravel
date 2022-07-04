<?php

namespace Jeffpereira\RealEstate\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\Project\Relationships;
use Jeffpereira\RealEstate\Models\Traits\Project\Scopes;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Image extends Model
{
    use UsesUuid;

    public $table = 'images_realestate';

    protected $fillable = [
        'way', 'alt', 'title', 'description', 'author'
    ];
}
