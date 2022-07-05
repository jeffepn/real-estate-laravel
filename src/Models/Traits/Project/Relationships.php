<?php

namespace Jeffpereira\RealEstate\Models\Traits\Project;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jeffpereira\RealEstate\Models\Common\Image;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Models\Project\ImageProject;

trait Relationships
{
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'image_projects')
            ->using(ImageProject::class);
    }

    public function imagesProject(): HasMany
    {
        return $this->hasMany(ImageProject::class);
    }
}
