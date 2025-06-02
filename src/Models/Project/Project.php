<?php

namespace Jeffpereira\RealEstate\Models\Project;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Model;
use Jeffpereira\RealEstate\Models\Traits\Project\Relationships;
use Jeffpereira\RealEstate\Models\Traits\Project\Scopes;
use Jeffpereira\RealEstate\Models\Traits\SetSlugByName;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use UsesUuid;
    use HasFactory;
    use SetSlugByName;
    use Relationships;
    use Scopes;

    protected $fillable = [
        'person_id', 'name', 'content',
    ];

    public function generateAltImage()
    {
        return sprintf(
            '%s de %s',
            Str::title($this->name),
            Str::title($this->responsible->name),
        );
    }

    protected static function newFactory()
    {
        return ProjectFactory::new();
    }
}
